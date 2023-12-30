<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaycutsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('paycuts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("worker_id")->index();
            $table->unsignedBigInteger("branch_id")->index();
            $table->decimal("value")->min(0);
            $table->string("note")->nullable();
            $table->timestamps();

            $table->foreign("worker_id")->references("id")->on("workers");
            $table->foreign("branch_id")->references("id")->on("branches");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('paycuts');
    }
}
