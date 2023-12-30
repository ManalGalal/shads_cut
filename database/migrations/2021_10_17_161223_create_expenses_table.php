<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string("name_en");
            $table->string("name_ar");
            $table->unsignedFloat("amount");
            $table->unsignedBigInteger("expense_category_id");
            $table->unsignedBigInteger("branch_id")->index();
            $table->string("note_en")->nullable();
            $table->string("note_ar")->nullable();
            $table->date("expense_date");
            $table->timestamps();

            $table->foreign("expense_category_id")->references("id")->on("expense_categories");
            $table->foreign("branch_id")->references("id")->on("branches");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('expenses');
    }
}
