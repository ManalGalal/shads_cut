<?php

use App\Models\Coupon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddAllMembershipToCoupons extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::statement("ALTER TABLE coupons MODIFY COLUMN membership ENUM('PLAT','GOLD','SILVER','BASIC','ALL')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Coupon::where("membership", "ALL")->orWhere("membership", "BASIC")->forceDelete();
        DB::statement("ALTER TABLE coupons MODIFY COLUMN membership ENUM('PLAT','GOLD','SILVER')");
    }
}
