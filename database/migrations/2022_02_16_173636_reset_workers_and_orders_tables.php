<?php

use App\Models\Order;
use App\Models\Worker;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetWorkersAndOrdersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Worker::whereRaw("1=1")->delete();
        DB::statement("ALTER TABLE workers AUTO_INCREMENT = 1");

        Order::whereRaw("1=1")->delete();
        DB::statement("ALTER TABLE orders AUTO_INCREMENT = 1");


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // no reverse migration
    }
}
