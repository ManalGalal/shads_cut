<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderStocksTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('order_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("order_id")->index();
            $table->unsignedBigInteger("stock_id")->index();
            $table->unsignedInteger("quantity")->default(1);
            $table->timestamps();
            $table->foreign("order_id")->references("id")->on("orders");
            $table->foreign("stock_id")->references("id")->on("stocks");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('order_stocks');
    }
}
