<?php

use App\Models\AppSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMainAppSettings extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $main_settings = [
        "POINTS_TO_WALLET" => "0.5",
        "ORDER_MONEY_TO_POINTS" => "2",
        "BASIC_PROGRAM_COLOR" => "#000000",
        "SILVER_PROGRAM_COLOR" => "#BAC1C8",
        "GOLD_PROGRAM_COLOR" =>  "#F8D996",
        "PLAT_PROGRAM_COLOR" => "#E5E4E2",
        "POINTS_TO_REACH_BASIC" => "1",
        "POINTS_TO_REACH_SILVER" => "1000",
        "POINTS_TO_REACH_GOLD" => "2000",
        "POINTS_TO_REACH_PLAT" => "3000",
        "PRIVACY_POLICY_EN" => "",
        "PRIVACY_POLICY_AR" => "",
        "TERMS_AND_CONDITIONS_EN" => "",
        "TERMS_AND_CONDITIONS_AR" => "",
    ];
    public function up() {
        foreach ($this->main_settings as $key => $value) {
            AppSetting::create(["name" => $key, "value" => $value, "main" => true]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        foreach ($this->main_settings as $key => $_) {
            AppSetting::where(["name" => $key])->delete();
        }
    }
}
