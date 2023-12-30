<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeUpdateToOrderStocks extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('order_stocks', function (Blueprint $table) {
            $table->dropColumn("quantity");
            $table->unsignedInteger("amount_used")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('order_stocks', function (Blueprint $table) {
            $table->dropColumn("amount_used");
            $table->unsignedInteger("quantity")->default(1);
        });
    }
}
