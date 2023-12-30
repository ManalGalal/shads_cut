<?php

namespace App\Console;

use App\Models\Milestone;
use App\Models\NotificationMessage;
use App\Models\Order;
use App\Models\User;
use App\Models\Worker;
use App\Models\WorkerSalary;
use App\Traits\NotificationTraits;
use Error;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;

class Kernel extends ConsoleKernel {
    use NotificationTraits;
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    use NotificationTraits;
    protected function schedule(Schedule $schedule) {
        /**
         * needs refactoring 
         */
        $schedule->call(function () {
            $workers = Worker::all();
            foreach ($workers as $worker) {
                $worker["accumlative_salary"] += $worker->monthly_salary / now()->daysInMonth;
                $worker->save();
            }
        })->daily()->description("Increment the accumlative_salary for each worker daily dendping on how much he make per month");
        $schedule->call(function () {
            Worker::whereRaw("1=1")
                ->update(["accumlative_salary" => 0]);
        })->monthly()->description("Set the accumlative_salary for all workers with 0 at the begining of each month");
        $schedule->call(function () {
            $today = now()->format("m-d");
            $users = User::whereRaw("users.birth_date LIKE '%-$today'")
                ->get();
            foreach ($users as $user) {
                $name = ucfirst($user->name);
                $notification_message = NotificationMessage::create([
                    "title_en" =>  "Happy Birthday $name",
                    "title_ar" =>    "عيد ميلاد سعيد $name",
                    "body_en" =>    "Shad's wish you a happy birthday with a discount 25%",
                    "body_ar" => "Shads يتمني لكل عيد ميلاد سعيد بخصم 25%"
                ]);
                $this->sendNotification(
                    $notification_message,
                    "user",
                    [$user->id]
                );
            }
        })->daily()->description("Send Notification if today is user's birthday");
        $schedule->call(function () {
            $users = User::all();
            foreach ($users as $user) {
                $days_after_creation = now()->diff($user->created_at)->days;
                if ($days_after_creation >= 90 && $days_after_creation % 90 == 0) {
                    Milestone::where("user_id", $user->id)
                        ->delete();
                }
            }
        })->daily()->description("Remove milestones every 90 days for each user");

        $schedule->call(function () {
            $formated_date = now()->format('Y-m-d');
            Order::whereRaw("DATEDIFF('$formated_date', orders.reservation_time) > 0 and orders.status in ('pending', 'scheduled', 'in_progress')")
                ->update(["status" => "canceled"]);
        })->daily()->description("Cancel orders that their reservation time has passed with more than 24 hours and with status [pending, in_progess, scheduled]");

        $schedule->call(function () {
            $workers = Worker::whereNull("left_at")
                ->with("additives", function ($query) {
                    $query->whereBetween("created_at", [now()->startOfMonth(), now()->endOfMonth()]);
                })
                ->with("paycuts", function ($query) {
                    $query->whereBetween("created_at", [now()->startOfMonth(), now()->endOfMonth()]);
                })
                ->get(); // Get workers who are working 
            foreach ($workers as $worker) {
                try {
                    $salary = $worker->monthly_salary + $worker["additives"]->sum("value") - $worker["paycuts"]->sum("value");
                    WorkerSalary::create([
                        "worker_id" => $worker->id,
                        "salary_date" => now()->endOfMonth(),
                        "expected_salary" => $salary,
                        "actual_salary" => $salary,
                        "total_paycuts" => $worker["paycuts"]->sum("value"),
                        "total_additives" => $worker["additives"]->sum("value"),
                        "notes_en" => "This Salary was created automatically if you have any notes contact the admin",
                        "notes_ar" => "هذا المرتب تم حسابه أليا اذا وجدت مشاكل برجاء التوجه الي المدير"
                    ]);
                } catch (Error $e) {
                    // Do nothing for now. 
                }
            }
        })->lastDayOfMonth('23:59')->description("Calculate Monthly Salary for each worker");
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands() {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
