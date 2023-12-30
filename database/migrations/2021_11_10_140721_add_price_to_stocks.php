<?php

use App\Models\OrderStock;
use App\Models\Stock;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceToStocks extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        // delete all stocks data
        OrderStock::whereRaw("1=1")->delete();
        Stock::whereRaw("1=1")->delete();
        Schema::table('stocks', function (Blueprint $table) {
            $table->unsignedFloat("price");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn("price");
        });
    }
}
