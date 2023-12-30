<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkDaysTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void 
     */
    protected $days_of_the_week = ["fri", "sat", "sun", "mon", "tue", "wed", "thu"];
    public function up() {

        Schema::create('work_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("worker_id")->index();
            $table->enum("day", $this->days_of_the_week);
            $table->boolean("on");
            $table->time("from")->default("09:00");
            $table->time("to")->default("17:00");
            $table->timestamps();
            $table->foreign("worker_id")->references("id")->on("workers");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('work_days');
    }
}
