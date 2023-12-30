<?php

use App\Models\Coupon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShadsCoupon extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        // if exist before delete it. 
        $deleted = Coupon::where("code", "SHADS")->forceDelete();

        Coupon::create([
            "code" => "SHADS",
            "value" => 10,
            "type" => "percentage",
            "starts_from" => "2022-01-01",
            "expires_at" => "2052-01-01",
            "category" => "all",
            "active" => true,
            "usuage_limit" => 1000000,
            "special" => true,
            "usages_per_user" => 1000
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Coupon::where("code", "SHADS")->forceDelete();
    }
}
