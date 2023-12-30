<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RechangeIdInTransactions extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        $starting_value = intval(env("TRANSACTION_STARTING_VALUE", 1000));
        DB::statement("ALTER TABLE transactions AUTO_INCREMENT = $starting_value");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::statement("ALTER TABLE transactions AUTO_INCREMENT = 1");
    }
}
