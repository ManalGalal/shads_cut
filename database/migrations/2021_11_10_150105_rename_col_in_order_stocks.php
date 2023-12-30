<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColInOrderStocks extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('order_stocks', function (Blueprint $table) {
            $table->renameColumn("amount_used", "used_amount");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('order_stocks', function (Blueprint $table) {
            $table->renameColumn("used_amount", "amount_used");
        });
    }
}
