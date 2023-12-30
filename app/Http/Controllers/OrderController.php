<?php

namespace App\Http\Controllers;

use App\Http\Requests\addDashboardDiscount;
use App\Http\Requests\adminCreateOrder;
use App\Http\Requests\adminOrderPayment;
use App\Http\Requests\adminUpdateOrder;
use App\Http\Requests\createOrder;
use App\Http\Requests\giveFeedback;
use App\Http\Requests\orderPayment;
use App\Http\Requests\refundOrder;
use App\Models\Additive;
use App\Models\Branch;
use App\Models\Log;
use App\Models\Order;
use App\Models\OrderPaymentMethod;
use App\Models\OrderWorker;
use App\Models\WorkerRate;
use App\Traits\AnalyticTraits;
use App\Traits\MilestoneTraits;
use App\Traits\OrderControllerTraits;
use App\Traits\OrderCreationTraits;
use App\Traits\OrderCreationValidation;
use App\Traits\PaymentTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class OrderController extends Controller {
    use OrderCreationTraits,
        OrderCreationValidation,
        AnalyticTraits,
        MilestoneTraits,
        PaymentTraits,
        OrderControllerTraits;
    public function create(createOrder $request) {
        $validated = $request->validated();
        $this->orderValidation($request, $validated);
        $validated["user_id"] = $request->user()->id;
        $validated["total_amount"] = $this->calculateTotalAmount($validated);
        $validated = $this->applyCoupon($request, $validated);
        $validated["status"] = "scheduled";
        $validated["source"] = "mobile";
        $order = Order::create($validated);
        $this->addOrderServices($validated, $order);
        $this->addOrderWorkers($validated, $order);
        $this->sendNotificationForAdmins($order);
        // must refresh order to update the tax parameter
        $order->refresh();
        return response(["message" => __("messages.order_created"), "order" => $order], 201);
    }
    public function createForAdmin(adminCreateOrder $request, Branch $branch) {
        $validated = $request->validated();
        $validated["branch_id"] = $branch->id;
        $this->orderValidation($request, $validated, false); // we don't need to validate address_id right now 
        $validated["total_amount"] = $this->calculateTotalAmount($validated);
        $validated["status"] = "scheduled";
        $validated = $this->applyCoupon($request, $validated);
        $order = Order::create($validated);
        $this->addOrderServices($validated, $order);
        $this->addOrderWorkers($validated, $order);
        $order->refresh();
        return response(["message" => __("messages.order_created"), "order" => $order], 201);
    }
    public function cancel(Request $request, Order $order) {
        if ($order["status"] === "completed") {
            return $this->BAD_REQUEST(__("errors.order_compeleted"));
        }
        if (Order::statusOccurrence($order->id, "canceled") >= 1) {
            return $this->BAD_REQUEST(__("errors.order_canceled_before"));
        }
        $this->descreaseUserPointsOnRefundingCompletedOrder($order->user, $order);
        // Delete additives on cancel. 
        Additive::where("order_id", $order->id)->delete();
        $order->update(["status" =>  "canceled"]);
        $order->save();
        return response(["message" => __("messages.order_canceled")]);
    }
    public function payOrder(orderPayment $request, Order $order) {
        $validated = $request->validated();
        if ($order->total_paid >= $order->total_amount) {
            $this->BAD_REQUEST(__("messages.cannot_pay_order"));
        }

        $user = $request->user();
        return $this->orderPayment($validated["payment_method"], $user, $order);
    }
    public function payOrderForAdmin(adminOrderPayment $request, Order $order) {
        $validated = $request->validated();
        if ($order->total_paid >= $order->total_amount) {
            $this->BAD_REQUEST(__("messages.cannot_pay_order"));
        }
        $user = $order->user;
        if ($validated["payment_method"] === "wallet") {
            return $this->orderPayment($validated["payment_method"], $user, $order);
        }
        $paid_amount = $order->total_amount - $order->total_paid;
        $order->total_paid += $paid_amount;
        $order->save();
        OrderPaymentMethod::create([
            "order_id" => $order->id,
            "payment_method" => $validated["payment_method"],
            "paid_amount" => $paid_amount
        ]);
        // Complete uncompleted-order to give additives to workers. 
        $order_workers = OrderWorker::where("completed", false)
            ->where("order_id", $order->id)
            ->get();
        foreach ($order_workers as $order_worker) {
            $order_worker->update(["completed" => true]);
        }
        return response(["message" => __("messages.order_paid")]);
    }
    public function myOrders(Request $request) {
        $orders = Order::where("user_id", $request->user()->id)
            ->with(["workers:id,name,profile_picture", "services.category", "branch", "payment_methods:payment_method,order_id,paid_amount"])
            ->orderByDesc("created_at")
            ->paginate($request->number)
            ->withQueryString();

        return response(["orders" => $orders]);
    }
    public function customerOrderById(Request $request, Order $order) {
        $order = Order::where("id", $order->id)
            ->with([
                "workers:id,name,profile_picture", "services.category", "address", "branch", "payment_methods",
                "order_products.product:id,name_en,name_ar,price,info_en,info_ar,category_id,brand_id,image",
                "order_products.product.brand:id,name_en,name_ar", "order_products.product.category:id,name_en,name_ar"
            ])
            ->first();
        return response(["order" => $order]);
    }
    public function updateForAdmin(adminUpdateOrder $request, Order $order) {
        $validated = $request->validated();
        // sepcial-case and no this is not monkey patching 
        // if invoice_generated set = true it can't be changed to false again 
        if (Arr::has($validated, "invoice_generated")) {
            $validated["invoice_generated"] = $order->invoice_generated == true ? true : $validated["invoice_generated"];
        }

        $order->total_amount -= $order->tax;
        $order->tax = 0;
        $validated["total_amount"] = $order->total_amount;
        if (!$order->coupon_id) {
            $validated = $this->applyCoupon($request, $validated);
        }

        $order->update($validated);
        $order->refresh();
        return response(["message" => __("messages.order_updated"), "order" => $order]);
    }
    public function dashboardDiscount(addDashboardDiscount $request, Order $order) {
        $validated = $request->validated();
        $orignal_total_amount = $order->total_amount - $order->tax;
        if ($validated["dashboard_discount"] > $orignal_total_amount) {
            return $this->BAD_REQUEST(__("errors.invalid_dashboard_discount"));
        }
        echo $orignal_total_amount;
        // removing tax and dashboard discount from original order's total amount
        $order->total_amount -= $order->tax + $validated["dashboard_discount"];
        $order->tax = 0;
        $order->dashboard_discount += $validated["dashboard_discount"];
        $order->save();
        return response(["message" => __("messages.order_updated")]);
    }
    public function refundOrder(refundOrder $request, Order $order) {
        $validated = $request->validated();
        $user = $order->user;
        $order_refunded = Log::where("table_name", "orders")
            ->where("col_name", "status")
            ->where("table_id", $order->id)
            ->where("value", "refunded")
            ->exists();
        if ($order_refunded) {
            return $this->BAD_REQUEST(__("errors.order_refunded_before"));
        }
        $this->descreaseUserPointsOnRefundingCompletedOrder($user, $order);
        // Delete additives on refund. 
        Additive::where("order_id", $order->id)->delete();
        $order->update([
            "status" => "refunded",
            "refund_reason_en" => $validated["refund_reason_en"] ?? null,
            "refund_reason_ar" => $validated["refund_reason_ar"] ?? null
        ]);
        if (Arr::has($validated, "return_to_wallet") && $validated["return_to_wallet"]) {
            $user = $order->user;
            $user->wallet += $order->total_paid;
            $user->save();
        }
        return response(["message" => __("messages.order_refunded")]);
    }
    public function branchOrders(Request $request) {
        $orders = Order::where("branch_id", $request->user()->branch_id)
            ->with(["user:id,name,phone,profile_picture", "services.category", "location", "payment_methods:payment_method,order_id,paid_amount"])
            ->orderByDesc("created_at")
            ->paginate()
            ->withQueryString();
        return response(["orders" => $orders]);
    }
    public function branchOrderById(Order $order) {
        $order = Order::where("id", $order->id)
            ->with(["workers:id,name,profile_picture", "services.category", "location", "stocks", "user:id,name,phone,profile_picture", "payment_methods:payment_method,order_id,paid_amount"])
            ->first();
        return response(["order" => $order]);
    }
    public function getWorkerOrders(Request $request) {
        [$from, $to] = $this->dateFilter($request);
        $orders = Order::select(["orders.*"])
            ->join("order_workers", "order_id", "=", "orders.id")
            ->where("worker_id", $request->user()->id)
            ->where("orders.created_at", ">=", $from)
            ->where("orders.created_at", "<=", $to)
            ->orderByDesc("orders.created_at")
            ->groupBy("orders.id")
            ->with(["user:id,name,phone,profile_picture", "services.category", "address.location", "location", "stocks"])
            ->paginate($request->number)
            ->withQueryString();

        return response(["orders" => $orders]);
    }
    public function getWorkerOrderByid(Request $request, Order $order) {
        //TODO: add order status to this query
        $order = $request->user()->orders()->where("order_id", $order->id)
            ->with([
                "user:id,name,phone,profile_picture", "services.category",
                "address.location", "location", "stocks", "branch",
                "order_products.product:id,name_en,name_ar,price,info_en,info_ar,category_id,brand_id,image",
                "order_products.product.brand:id,name_en,name_ar", "order_products.product.category:id,name_en,name_ar"
            ])->first();
        if (!$order) {
            return $this->UNAUTHORIZED();
        }
        return response(["order" => $order]);
    }
    public function giveFeedback(giveFeedback $request, Order $order) {
        $validated = $request->validated();

        if ($order->status !== "completed") {
            return $this->BAD_REQUEST(__("errors.order_not_completed"));
        }
        if ($order->rating !== null) {
            return $this->BAD_REQUEST(__("errors.order_is_rated"));
        }
        $order->update($validated);
        $workers = $order->workers;
        foreach ($workers as $worker) {
            WorkerRate::create([
                "user_id" => $request->user()->id,
                "worker_id" => $worker->id,
                "order_id" => $order->id,
                "rate" => $validated["rating"]
            ]);
        }
        return response(["message" => __("messages.feedback_sent")]);
    }
}
