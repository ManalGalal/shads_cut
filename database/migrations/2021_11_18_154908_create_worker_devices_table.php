<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerDevicesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('worker_devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("worker_id")->index();
            $table->string("device_id");
            $table->string("device_token");
            $table->timestamps();
            $table->foreign("worker_id")->references("id")->on("workers");
        });
    }


    public function down() {
        Schema::dropIfExists('worker_devices');
    }
}
