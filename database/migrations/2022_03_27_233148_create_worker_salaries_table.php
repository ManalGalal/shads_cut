<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerSalariesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('worker_salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("worker_id");
            $table->date("salary_date");
            $table->unsignedFloat("expected_salary");
            $table->unsignedFloat("actual_salary");
            $table->unsignedFloat("total_paycuts")->default(0);
            $table->unsignedFloat("total_additives")->default(0);
            $table->string("notes_en")->nullable();
            $table->string("notes_ar")->nullable();
            $table->timestamps();
            $table->foreign("worker_id")->references("id")->on("workers")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('worker_salaries');
    }
}
