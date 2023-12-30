<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeBranchIdNullableInAdmins extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('admins', function (Blueprint $table) {
            $table->unsignedBigInteger("branch_id")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('admins', function (Blueprint $table) {
            $table->unsignedBigInteger("branch_id")->nullable(false)->change();
        });
    }
}
