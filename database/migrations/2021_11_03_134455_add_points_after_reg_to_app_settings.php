<?php

use App\Models\AppSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPointsAfterRegToAppSettings extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $name = "POINTS_AFTER_REGISTERATION";
    public function up() {
        AppSetting::create(["name" => $this->name, "value" => 100, "main" => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        AppSetting::where("name", $this->name)->delete();
    }
}
