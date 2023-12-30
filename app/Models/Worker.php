<?php

namespace App\Models;

use App\Traits\BelongsToBranch;
use App\Traits\HasProfilePicture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\PhoneLogin;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;
use Laravel\Passport\HasApiTokens;

class Worker extends Authenticatable {

    use HasFactory, HasApiTokens, PhoneLogin, BelongsToBranch, HasProfilePicture, HasProfilePicture;
    protected $fillable = [
        "name", "gender", "age", "phone", "email",
        "password", "branch_id", "monthly_salary",
        "job_title", "profile_picture", "flag",
        "started_at", "left_at", "accumlative_salary",
        "lang"
    ];
    protected $hidden = [
        "password",
        "remember_token",
        "pivot"
    ];
    public function services() {
        return $this->belongsToMany(Service::class, "worker_services");
    }
    public function work_days() {
        return $this->hasMany(WorkDay::class, "worker_id");
    }
    public static function servicesWorkersForBranch($service_ids = [], $branch_id = 1, $date) {
        $workers = Worker::select(["workers.id", "workers.name", "workers.profile_picture"])
            ->join("worker_services", "workers.id", "=", "worker_services.worker_id")
            ->whereIn("worker_services.service_id", $service_ids)
            ->where("workers.branch_id", $branch_id)
            ->groupBy("workers.id")
            ->with(["worker_rates:worker_id,rate"])
            ->get();
        $next_week = new Carbon($date);
        $next_week->addDays(8);
        foreach ($workers as $worker) {
            $worker->is_avaliable(new Carbon($date));
            // you have to make sure to pass this by value.
            $worker->calendar(new Carbon($date), new Carbon($next_week));
        }
        return $workers;
    }
    public function worker_devices() {
        return $this->hasMany(WorkerDevice::class);
    }
    public function worker_rates() {
        return $this->hasMany(WorkerRate::class);
    }
    public function orders() {
        return $this->belongsToMany(Order::class, "order_workers")->distinct();
    }
    public function is_avaliable(Carbon $date) {
        // check if worker is avaliable right now 
        // look in session 
        $date->setLocale("en");
        $in_session = Redis::command("HEXISTS", ["session", $this->id]);

        // check if worker is on this day...
        $day_name = lcfirst(substr($date->dayName, 0, 3)); // "Monday" => "mon", "Friday" => "fri"
        $order_hour = $date->format("H:i:s"); // 17:33:26
        $work_day = WorkDay::where("day", $day_name)
            ->where("worker_id", $this->id)
            ->where("from", "<=", $order_hour)
            ->where("to", ">", $order_hour)
            ->whereRaw("work_days.from < work_days.to")
            ->first();
        if (!$work_day) {
            $work_day = WorkDay::where("day", $day_name)
                ->where("worker_id", $this->id)
                ->where("from", "<=", $order_hour)
                ->where("to", "<", $order_hour)
                ->whereRaw("work_days.from > work_days.to")
                ->first();
        }
        if (!$work_day || !$work_day->on) {
            $this["avaliable"] = false;
            $this["avaliable_now"] = !$in_session;
            return;
        }
        // look in day_offs 
        $has_day_offs = DayOff::where("worker_id", $this->id)
            ->where("day", $date->format("Y-m-d")) // 2022-01-15 // Y must be capital to get full year or else you will get 22 without 20
            ->where("status", "accepted")
            ->exists();
        if ($has_day_offs) {
            $this["avaliable"] = false;
            $this["avaliable_now"] = !$in_session;
            return;
        }
        $first_order_before = Order::selectRaw("orders.reservation_time,orders.id")
            ->join("order_workers", "order_id", "=", "orders.id")
            ->where("order_workers.worker_id", $this->id)
            ->where("orders.reservation_time", "<", '\'' . $date->format("Y-m-d H:i:s") . '\'')
            ->whereIn("orders.status", ["'pending'", "'scheduled'", "'in_progress'"])
            ->groupBy("orders.id")
            ->orderByDesc("orders.reservation_time")
            ->first();
        if ($first_order_before) {
            $estimated_starts_at = new Carbon($first_order_before->reservation_time);
            // echo "before starts_at " . $estimated_starts_at . "\n";
            $services_estimated_time = $first_order_before->services->sum("estimated_time") ?? 0;
            $estimated_ends_at = $estimated_starts_at->addMinutes($services_estimated_time);
            // echo $estimated_ends_at;
            // echo "before ends_at " . $estimated_ends_at . "\n";
            if ($estimated_ends_at > $date) { // making sure date is our of range 
                $this["avaliable"] = false;
                $this["avaliable_now"] = !$in_session;
                return;
            }
        }
        $first_order_after = Order::selectRaw("orders.reservation_time,orders.id")
            ->join("order_workers", "order_id", "=", "orders.id")
            ->where("order_workers.worker_id", $this->id)
            ->where("orders.reservation_time", ">=", $date->format("Y-m-d H:i:s"))
            ->whereIn("orders.status", ["pending", "scheduled", "in_progress"])
            ->groupBy("orders.id")
            ->orderBy("orders.reservation_time", "asc")
            ->first();
        if ($first_order_after) {
            $estimated_starts_at = new Carbon($first_order_after->reservation_time);
            // echo "after starts_at " . $estimated_starts_at . "\n";
            if ($date >= $estimated_starts_at) {
                $this["avaliable"] = false;
                $this["avaliable_now"] = !$in_session;
                return;
            }
        }

        $this["avaliable"] = true;
        $this["avaliable_now"] = !$in_session;
    }
    /**
     * Made to differntiate between workers on
     */
    public function isWorker() {
        return true;
    }
    public function isSuperAdmin() {
        return false;
    }
    /**
     * Worker calendar over a time period
     * structure 
     * [ 
     *   "Y-m-d" => [
     *        "on" => Boolean
     *         "from" => "H:i" 
     *         "to" => "H:i"
     *         "orders" => [
     *           [
     *             "order_id" => Order Id 
     *             "number_of_services" => Number
     *             "estimated_start_time" => "H:i:s",
     *             "estimated_end_time" => "H:i:s" 
     *           ]
     *         ]
     *        "off_reason" => ENUM ("normal off day", *one of day of reasons*)
     *    ]
     * ]
     */
    public function calendar($from = null, $to = null) {
        $from = Carbon::checkValid($from) ? $from :  now();
        $to = Carbon::checkValid($to) ? $to : now()->addMonth();
        $from->setlocale("en");
        $to->setlocale("en");
        if ($from > $to) {
            $to = new Carbon($from);
            $to->addMonth();
        }
        $worker_orders = Worker::workersOrderCalender([$this->id]);
        $calendar = [];
        while ($from < $to) {

            $day_name = lcfirst(substr($from->dayName, 0, 3)); // fri ... 
            $day_off = DayOff::where("worker_id", $this->id)
                ->where("day", $from->format("Y-m-d")) // 2022-01-15 // Y must be capital to get full year or else you will get 22 without 20
                ->where("status", "accepted")
                ->first();
            if ($day_off) {
                $calendar[] = [
                    "date" => $from->format("Y-m-d"),
                    "on" => false,
                    "from " => null,
                    "to" => null,
                    "orders" => [],
                    "off_reason" => $day_off->reason
                ];
                $from->addDay();
                continue;
            }
            $work_day = WorkDay::where("worker_id", $this->id)
                ->where("day", $day_name)
                ->first();
            if (!$work_day->on) {
                $calendar[] = [
                    "date" => $from->format("Y-m-d"),
                    "on" => false,
                    "from " => null,
                    "to" => null,
                    "orders" => [],
                    "off_reason" => "normal day off"
                ];
                $from->addDay();
                continue;
            }
            $this->temp_from = $from;
            $orders = $worker_orders->filter(function ($worker_order) {
                return $worker_order->day == $this->temp_from->format("Y-m-d");
            });
            $mapped_orders = [];
            foreach ($orders as $order) {
                $mapped_orders[] =
                    [
                        "start_time" => $order->estimated_start_time,
                        "estimated_end_time" => $order->estimated_end_time
                    ];
            }
            $calendar[] = [
                "date" => $from->format("Y-m-d"),
                "on" => true,
                "from " => $work_day->from,
                "to" => $work_day->to,
                "orders" => $mapped_orders,
                "off_reason" => null
            ];
            $from->addDay();
        }
        unset($this["temp_from"]);
        $this["calendar"] = $calendar;
        return;
    }
    public static function workersOrderCalender($workers = []) {
        if (!$workers) {
            return [];
        }
        return Order::selectRaw("workers.name as worker_name,
        worker_id,
        orders.id as order_id,
        DATE_FORMAT(reservation_time, '%Y-%m-%d') as day, DAYNAME(reservation_time) as day_name,
        DATE_FORMAT(reservation_time, '%H:%i') as estimated_start_time,
        DATE_FORMAT(DATE_ADD(orders.reservation_time,INTERVAL SUM(services.estimated_time) MINUTE), '%H:%i') as estimated_end_time,
        count(services.id) as number_of_services")
            ->join("order_workers", "orders.id", "=", "order_workers.order_id")
            ->join("services", "order_workers.service_id", "=", "services.id")
            ->join("workers", "order_workers.worker_id", "=", "workers.id")
            ->whereIn("orders.status", ["pending", "scheduled", "in_progress"])
            ->whereIn("workers.id", $workers)
            ->groupByRaw("orders.id,workers.id")
            ->get();
    }
    public function additives() {
        return $this->hasMany(Additive::class)->orderBy("created_at", "desc");
    }
    public function paycuts() {
        return $this->hasMany(Paycut::class)->orderBy("created_at", "desc");
    }
    public function salaries() {
        return $this->hasMany(WorkerSalary::class);
    }
}
