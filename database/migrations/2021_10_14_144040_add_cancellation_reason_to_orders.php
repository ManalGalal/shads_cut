<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCancellationReasonToOrders extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (Schema::hasColumn("orders", "cancellation_reason_id")) {
            Schema::dropColumns("orders", "cancellation_reason_id");
        }
        Schema::table('orders', function (Blueprint $table) {

            $table->unsignedBigInteger("cancellation_reason_id")->nullable();
            $table->foreign("cancellation_reason_id")->references("id")->on("cancellation_reasons");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId("cancellation_reason_id");
        });
    }
}
