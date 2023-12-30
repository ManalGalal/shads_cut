<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string("name_en");
            $table->string("name_ar");
            $table->string("image")->nullable();
            $table->string("description_en")->nullable();
            $table->string("description_ar")->nullable();
            $table->unsignedInteger("quantity");
            $table->boolean("stock_availability")->default(true);
            $table->boolean("multi_use")->default(true);
            $table->unsignedInteger("usage")->default(0);
            $table->unsignedBigInteger("branch_id")->index();
            $table->timestamps();
            $table->foreign("branch_id")->references("id")->on("branches");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('stocks');
    }
}
