<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdatesToUsers extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('users', function (Blueprint $table) {
            $table->string("fb_id")->nullable();
            $table->string("google_id")->nullable();
            $table->string("apple_id")->nullable();
            $table->unsignedInteger("points")->default(0);
            $table->boolean("shads")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(["fb_id", "google_id", "apple_id", "points", "shads"]);
        });
    }
}
