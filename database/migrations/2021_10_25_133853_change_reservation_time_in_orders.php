<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeReservationTimeInOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN reservation_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // no reverse migration
        DB::statement("ALTER TABLE orders MODIFY COLUMN reservation_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");

    }
}
