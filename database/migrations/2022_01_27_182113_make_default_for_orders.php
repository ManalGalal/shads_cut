<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MakeDefaultForOrders extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'scheduled','in_progress','canceled', 'completed', 'refunded') DEFAULT 'scheduled'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'scheduled','in_progress','canceled', 'completed', 'refunded')");
    }
}
