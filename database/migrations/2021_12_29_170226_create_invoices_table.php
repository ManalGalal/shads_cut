<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->enum("invoice_type", ["cash_in", "cash_out"]);
            $table->boolean("paid")->default(false);
            $table->unsignedBigInteger("order_id")->nullable();
            $table->unsignedBigInteger("expense_id")->nullable();
            $table->unsignedFloat("total_amount");
            $table->timestamp("paid_at")->nullable();
            $table->mediumText("description_en")->nullable();
            $table->mediumText("description_ar")->nullable();
            $table->string("payment_method")->nullable(); // on cash_out 
            $table->boolean("printed")->default(false);
            $table->timestamps();
            $table->foreign("order_id")->references("id")->on("orders")->onDelete("set null");
            $table->foreign("expense_id")->references("id")->on("expenses")->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('invoices');
    }
}
