<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Traits\TransactionTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;


class PaymentController extends Controller {
    use TransactionTraits;
    /**
     * Get response callback 
     * @return HTML as web view indicating failuer or success.
     */

    public function paymobResonseCallback(Request $request) {
        $data = $request->query();
        if ($data["success"] == "false") {
            $file_path = resource_path("html/Payment-Page -Faliure.html");
            $file = fopen($file_path, "r");
            $failure_content = fread($file, filesize($file_path));
            fclose($file);
            return response($failure_content,400)
                ->header("content-type", "text/html");
        }
        $file_path = resource_path("html/Payment-Page.html");
        $file = fopen($file_path, "r");
        $success_content = fread($file, filesize($file_path));
        fclose($file);
        return response($success_content, 200)
            ->header("content-type", "text/html");
    }
    /**
     * update the payment of this transaction based on failuer or success.
     * @return null
     */
    public function paymobProcessedCallback(Request $request) {

        $data = $request->all()["obj"];
        $transaction_id = Arr::get($data, "order.merchant_order_id");
        $transaction = Transaction::where("id", $transaction_id)
            ->first();
        $transaction["paymob_id"] = $data["id"];
        $order = $transaction->order;
        $user = $transaction->user;
        if (!$data["success"]) {
            $transaction->status = "failed";
            $transaction->save();
            return response(true, 200); // I think it doesn't matter which status code youu return. 
        }
        $transaction->status = "successfull";
        $transaction->save();
        $this->sendNotificationOnSuccess($user);
        $this->updateOrderOnSuccess($transaction, $order);
        return response(true, 200);
    }
}
