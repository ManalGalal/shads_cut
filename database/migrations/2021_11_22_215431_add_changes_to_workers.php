<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChangesToWorkers extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('workers', function (Blueprint $table) {
            $table->timestamp("started_at")->nullable();
            $table->timestamp("left_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('workers', function (Blueprint $table) {
            $table->dropColumn(["started_at", "left_at"]);
        });
    }
}
