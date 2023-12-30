<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddMoreMethodsInOrderPaymentMethods extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::statement("ALTER TABLE order_payment_methods MODIFY COLUMN payment_method ENUM('cash','card','card_on_arrival','mobile_wallet','wallet','lucky', 'waffarha') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::statement("ALTER TABLE order_payment_methods MODIFY COLUMN payment_method ENUM('cash','card','card_on_arrival','mobile_wallet','wallet') NOT NULL");
    }
}
