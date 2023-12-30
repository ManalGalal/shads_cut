<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerRatesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('worker_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("worker_id")->index();
            $table->unsignedBigInteger("order_id");
            $table->unsignedBigInteger("user_id");
            $table->unsignedFloat("rate")->max(5);
            $table->timestamps();

            $table->foreign("worker_id")->references("id")->on("workers")->onDelete("cascade");
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->foreign("order_id")->references("id")->on("orders")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('worker_rates');
    }
}
