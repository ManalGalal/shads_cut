<?php

use App\Models\AppSetting;
use Illuminate\Database\Migrations\Migration;

class AddMembershipPointsToAppSettings extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        AppSetting::create(["name" => "PLAT_ORDER_MONEY_TO_POINTS", "value" => 5, "data_type" => "numeric", "main" => true]);
        AppSetting::create(["name" => "GOLD_ORDER_MONEY_TO_POINTS", "value" => 4, "data_type" => "numeric", "main" => true]);
        AppSetting::create(["name" => "SILVER_ORDER_MONEY_TO_POINTS", "value" => 3, "data_type" => "numeric", "main" => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        AppSetting::whereIn("name", ["PLAT_ORDER_MONEY_TO_POINTS", "GOLD_ORDER_MONEY_TO_POINTS", "SILVER_ORDER_MONEY_TO_POINTS"])
            ->delete();
    }
}
