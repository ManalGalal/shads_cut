<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChangesToProducts extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('products', function (Blueprint $table) {
            $table->string("sku")->nullable();
            $table->string("barcode")->nullable();
            $table->unsignedInteger("quantity")->default(0);
            $table->unsignedBigInteger("commission")->default(0)->max(100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(["sku", "barcode", "quantity", "commission"]);
        });
    }
}
