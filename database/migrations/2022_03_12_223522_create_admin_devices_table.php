<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminDevicesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('admin_devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("admin_id")->index();
            $table->string("device_id");
            $table->string("device_token");
            $table->timestamps();
            $table->foreign("admin_id")->references("id")->on("admins");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('admin_devices');
    }
}
