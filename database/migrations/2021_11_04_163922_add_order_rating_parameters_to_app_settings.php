<?php

use App\Models\AppSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderRatingParametersToAppSettings extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        AppSetting::create([
            "name" => "ORDER_RATING_PARAMTERS", "value" => "cleanliness,services,atmosphere,receptionist,overall",
            "main" => true, "data_type" => "string_arr"
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        AppSetting::where("name", "ORDER_RATING_PARAMTERS")->delete();
    }
}
