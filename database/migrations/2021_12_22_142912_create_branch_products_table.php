<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchProductsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('branch_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("branch_id")->index();
            $table->unsignedBigInteger("product_id");
            $table->unsignedInteger("quantity")->default(0);
            $table->unsignedBigInteger("commission")->default(0)->max(100);
            $table->timestamps();
            $table->foreign("branch_id")->references("id")->on("branches")->onDelete("cascade");
            $table->foreign("product_id")->references("id")->on("products")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('branch_products');
    }
}
