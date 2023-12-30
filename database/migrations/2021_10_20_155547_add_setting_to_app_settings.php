<?php

use App\Models\AppSetting;
use Illuminate\Database\Migrations\Migration;

class AddSettingToAppSettings extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        AppSetting::create(["name" => "MIN_POINTS_TO_REDEEM", "value" => 1000, "main" => true, "private" => false]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        AppSetting::where("name", "MIN_POINTS_TO_REDEEM")
            ->delete();
    }
}
