<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangesInAdditives extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('additives', function (Blueprint $table) {
            $table->unsignedBigInteger("product_id")->nullable();
            $table->unsignedInteger("quantity")->nullable();
            $table->foreign("product_id")->references("id")->on("products")->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('additives', function (Blueprint $table) {
            $table->dropForeign(["product_id"]);
            $table->dropColumn(["product_id", "quantity"]);
        });
    }
}
