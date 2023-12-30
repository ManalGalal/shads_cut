<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeTimeInOrderWorkers extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        DB::statement("ALTER TABLE order_workers MODIFY start_time TIMESTAMP NULL");
        DB::statement("ALTER TABLE order_workers MODIFY end_time TIMESTAMP NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('order_workers', function (Blueprint $table) {
            $table->date("start_time")->nullable()->change();
            $table->date("end_time")->nullable()->change();
        });
    }
}
