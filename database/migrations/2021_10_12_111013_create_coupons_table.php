<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string("code")->unique();
            $table->unsignedInteger("value")->min(0);
            $table->date("starts_from");
            $table->date("expires_at");
            $table->enum("category", ["indoor", "outdoor", "home", "all"]);
            $table->enum("type", ["fixed", "percentage"]);
            $table->boolean("active")->default(true);
            $table->softDeletes();
            $table->unsignedInteger("usage_limit")->default(1000);
            $table->unsignedInteger("usage_number")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('coupons');
    }
}
