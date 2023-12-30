<?php

use App\Models\OrderWorker;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOrderWorkers extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::statement("DELETE FROM order_workers WHERE NOT EXISTS (SELECT 1 FROM orders WHERE orders.id = order_workers.order_id)"); 
        //
        Schema::table('order_workers', function (Blueprint $table) {
            $table->foreign("order_id")->references("id")->on("orders")->onDelete("cascade");
            $table->foreign("worker_id")->references("id")->on("workers")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('order_workers', function (Blueprint $table) {
            $table->dropForeign(["order_id"]);
            $table->dropForeign(["worker_id"]);
        });
    }
}
