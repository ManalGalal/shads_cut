<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOnlineToServices extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('services', function (Blueprint $table) {
            $table->boolean("online")->default(true);
            $table->unsignedInteger("commission")->default(0)->max(100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(["online", "commission"]);
        });
    }
}
