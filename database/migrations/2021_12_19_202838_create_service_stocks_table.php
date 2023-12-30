<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceStocksTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('service_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("service_id")->index();
            $table->unsignedBigInteger("stock_id");
            $table->unsignedInteger("used_amount")->default(1);
            $table->timestamps();
            $table->foreign("service_id")->references("id")->on("services")->onDelete("cascade");
            $table->foreign("stock_id")->references("id")->on("stocks")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('service_stocks');
    }
}
