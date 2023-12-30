<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDayOffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_offs', function (Blueprint $table) {
            $table->id();
            $table->date("day");
            $table->unsignedBigInteger("worker_id")->index();
            $table->enum("status",["accepted","rejected","pending"])->default("pending");
            $table->string("reason")->nullable();
            $table->timestamps();
            $table->foreign("worker_id")->references("id")->on("workers");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('day_offs');
    }
}
