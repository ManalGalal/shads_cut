<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string("name_en");
            $table->string("name_ar");
            $table->string("address_en");
            $table->string("address_ar");
            $table->string("info_en");
            $table->string("info_ar");
            $table->unsignedBigInteger("location_id");
            $table->timestamps();

            $table->foreign("location_id")->references("id")->on("locations");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('branches');
    }
}
