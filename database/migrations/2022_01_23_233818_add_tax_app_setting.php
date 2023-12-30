<?php

use App\Models\AppSetting;
use Illuminate\Database\Migrations\Migration;

class AddTaxAppSetting extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        AppSetting::create(["name" => "TAX", "value" => 14, "main" => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        AppSetting::where("name", "TAX")
            ->delete();
    }
}
