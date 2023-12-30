<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatesInBranchProducts extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('branch_products', function (Blueprint $table) {
            $table->unsignedInteger("min_quantity")->default(0);
            $table->unsignedInteger("max_quantity")->default(1000);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('branch_products', function (Blueprint $table) {
            $table->dropColumn(["min_quantity", "max_quantity"]);
        });
    }
}
