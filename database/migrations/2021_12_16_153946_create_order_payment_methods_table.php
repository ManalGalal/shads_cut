<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPaymentMethodsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('order_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("order_id")->index();
            $table->enum("payment_method", ["cash", "card", "card_on_arrival", "mobile_wallet", "wallet"]);
            $table->unsignedFloat("paid_amount");
            $table->timestamps();
            $table->foreign("order_id")->references("id")->on("orders")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *  
     * @return void
     */
    public function down() {
        Schema::dropIfExists('order_payment_methods');
    }
}
