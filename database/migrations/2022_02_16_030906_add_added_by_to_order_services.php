<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddedByToOrderServices extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('order_services', function (Blueprint $table) {
            $table->string("added_by")->nullable();
            $table->unsignedBigInteger("added_by_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('order_services', function (Blueprint $table) {
            $table->dropColumn(["added_by", "added_by_id"]);
        });
    }
}
