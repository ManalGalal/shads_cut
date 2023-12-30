<?php

use App\Models\Module;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferalCodesToModule extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Module::create(["name" => "referal_codes"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Module::where("name", "referal_codes")->delete();
    }
}
