<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Remove3000PointsFromUsers extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::statement("UPDATE users SET points = points - 3000 WHERE points >= 5000;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        // No need for this but.. I had to do it
        DB::statement("UPDATE users SET points  = points + 3000 WHERE points + 3000 >= 5000;");
    }
}
