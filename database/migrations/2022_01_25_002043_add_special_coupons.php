<?php

use App\Models\Coupon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpecialCoupons extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Coupon::create([
            "code" => "GOLD_MEMBERSHIP",
            "value" => 5,
            "type" => "percentage",
            "active" => 1,
            "starts_from" => "2022-01-01",
            "expires_at" => "2050-01-01",
            "usage_limit" => 100000,
            "category" => "all",
            "special" => true
        ]);
        Coupon::create([
            "code" => "SILVER_MEMBERSHIP",
            "value" => 5,
            "type" => "percentage",
            "active" => 1,
            "starts_from" => "2022-01-01",
            "expires_at" => "2050-01-01",
            "usage_limit" => 100000,
            "category" => "all",
            "special" => true
        ]);
        Coupon::create([
            "code" => "PLAT_MEMBERSHIP",
            "value" => 5,
            "type" => "percentage",
            "active" => 1,
            "starts_from" => "2022-01-01",
            "expires_at" => "2050-01-01",
            "usage_limit" => 100000,
            "category" => "all",
            "special" => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Coupon::whereIn("code", ["GOLD_MEMEBERSHIP", "SILVER_MEMBERSHIP", "PLAT_MEMBERSHIP"])
            ->delete();
    }
}
