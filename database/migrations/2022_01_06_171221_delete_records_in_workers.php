<?php

use App\Models\Worker;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DeleteRecordsInWorkers extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Worker::whereRaw("1=1")->delete();
        DB::statement("ALTER TABLE workers AUTO_INCREMENT = 1");
        /**
         * Add Shady as the first worker
         */

        Worker::create([
            "name" => "Shady Elhosseny",
            "job_title" => "CEO",
            "phone" => "+201112400050",
            "password" => Hash::make("12345678"),
            "monthly_salary" => 0,
            "started_at" => now(),
            "age" => 30,
            "branch_id" => 1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
    }
}
