<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddMoreStatusToOrders extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'scheduled','in_progress','canceled', 'completed')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'scheduled','canceled', 'completed')");
    }
}
