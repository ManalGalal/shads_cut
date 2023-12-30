<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchIdToWorkDays extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('work_days', function (Blueprint $table) {
            $table->unsignedBigInteger("branch_id")->nullable();
            $table->unsignedBigInteger("worker_id")->nullable()->change();
            $table->foreign("branch_id")->references("id")->on("branches")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('work_days', function (Blueprint $table) {
            $table->dropForeign(["branch_id"]);
            $table->dropColumn("branch_id");
            $table->unsignedBigInteger("worker_id")->change();
        });
    }
}
