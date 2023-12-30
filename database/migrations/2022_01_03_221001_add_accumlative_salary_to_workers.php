<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccumlativeSalaryToWorkers extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('workers', function (Blueprint $table) {
            $table->float("accumlative_salary")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('workers', function (Blueprint $table) {
            $table->dropColumn("accumlative_salary");
        });
    }
}
