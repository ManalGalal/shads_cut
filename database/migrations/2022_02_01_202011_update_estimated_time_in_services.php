<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEstimatedTimeInServices extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('services', function (Blueprint $table) {
            $table->integer("estimated_time")->min(1)->max(24 * 60)->default(30)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('services', function (Blueprint $table) {
            $table->integer("estimated_time")->min(1)->max(120)->default(30);
        });
    }
}
