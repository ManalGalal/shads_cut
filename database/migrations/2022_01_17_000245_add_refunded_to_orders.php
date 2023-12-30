<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRefundedToOrders extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'scheduled','in_progress','canceled', 'completed', 'refunded')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'scheduled','in_progress','canceled', 'completed')");
    }
}
