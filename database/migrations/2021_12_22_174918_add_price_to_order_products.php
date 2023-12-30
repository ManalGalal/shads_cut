<?php

use App\Models\OrderProduct;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceToOrderProducts extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        // make sure to remove all old records
        OrderProduct::whereRaw("1=1")->delete();
        Schema::table('order_products', function (Blueprint $table) {
            $table->unsignedFloat("price");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('order_products', function (Blueprint $table) {
            $table->dropColumn("price");
        });
    }
}
