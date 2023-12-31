<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseCategoriesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string("name_en")->unique();
            $table->string("name_ar")->unique();
            $table->string("description_en")->nullable();
            $table->string("description_ar")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('expense_categories');
    }
}
