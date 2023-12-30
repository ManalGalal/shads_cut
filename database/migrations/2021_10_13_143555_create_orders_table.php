<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->enum("type", ["indoor", "outdoor", "home"]);
            $table->unsignedBigInteger("branch_id")->index();
            $table->unsignedBigInteger("user_id");  
            $table->unsignedBigInteger("coupon_id")->nullable();
            $table->unsignedBigInteger("address_id")->nullable();
            $table->unsignedBigInteger("location_id")->nullable();
            $table->unsignedFloat("total_amount");
            $table->unsignedFloat("total_paid")->default(0);
            $table->unsignedFloat("discounted_amount")->default(0);
            $table->enum("status", ["pending", "scheduled", "canceled", "completed"]);
            $table->timestamp("reservation_time");
            $table->timestamp("started_at")->nullable()->default(null);
            $table->timestamp("ended_at")->nullable()->default(null);
            $table->timestamps();
            $table->foreign("branch_id")->references("id")->on("branches");

            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("coupon_id")->references("id")->on("coupons");
            $table->foreign("address_id")->references("id")->on("addresses");
            $table->foreign("location_id")->references("id")->on("locations");
        });
    }

    /** 
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('orders');
    }
}
