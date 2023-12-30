<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_workers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("order_id")->index();
            $table->unsignedBigInteger("worker_id")->index();
            $table->date("start_time")->nullable();
            $table->date("end_time")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_workers');
    }
}
