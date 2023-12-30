<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->index();
            $table->unsignedBigInteger("location_id");
            $table->string("name");
            $table->unsignedBigInteger("region_id");
            $table->string("street");
            $table->boolean("home")->default(false);
            $table->unsignedInteger("building")->nullable();
            $table->unsignedInteger("floor")->nullable();
            $table->unsignedInteger("appartment")->nullable();
            $table->string("comment")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('addresses');
    }
}
