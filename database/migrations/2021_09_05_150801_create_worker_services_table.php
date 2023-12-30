<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerServicesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('worker_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("worker_id");
            $table->unsignedBigInteger("service_id")->index();
            $table->foreign("worker_id")->references("id")->on("workers");
            $table->foreign("service_id")->references("id")->on("services");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('worker_services');
    }
}
