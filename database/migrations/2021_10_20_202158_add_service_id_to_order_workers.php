<?php

use App\Models\OrderWorker;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceIdToOrderWorkers extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        // Remove test data
        // We don't want service_id to be nullable
        OrderWorker::whereRaw("1=1")
            ->delete(); 
        Schema::table('order_workers', function (Blueprint $table) {
            $table->unsignedBigInteger("service_id");
            $table->foreign("service_id")->references("id")->on("services");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('order_workers', function (Blueprint $table) {
            $table->dropForeign(["service_id"]);
            $table->dropColumn("service_id");
        });
    }
}
