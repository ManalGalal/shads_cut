<?php

use App\Models\AppSetting;
use Illuminate\Database\Migrations\Migration;

class AddReferalPointsToAppSettings extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        AppSetting::create(["name" => "REFERAL_POINTS", "value" => 100, "main" => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        AppSetting::where("name", "REFERAL_POINTS")->delete();
    }
}
