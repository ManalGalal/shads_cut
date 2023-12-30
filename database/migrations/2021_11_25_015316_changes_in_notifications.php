<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangesInNotifications extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('notifications', function (Blueprint $table) {
            $table->renameColumn("title", "title_en");
            $table->unsignedBigInteger("user_id")->nullable()->change();
            $table->unsignedBigInteger("worker_id")->nullable();
            $table->string("title_ar")->nullable();
            $table->foreign("worker_id")->references("id")->on("workers")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('notifications', function (Blueprint $table) {
            $table->renameColumn("title_en", "title");
            $table->unsignedBigInteger("user_id")->change();
            $table->dropForeign(["worker_id"]);
            $table->dropColumn(["worker_id", "title_ar"]);
        });
    }
}
