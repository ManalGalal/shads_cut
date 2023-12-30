<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataTypeToAppSettings extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('app_settings', function (Blueprint $table) {
            $table->text("value")->change();
            $table->enum("data_type", ["boolean", "string", "numeric", "numeric_arr", "string_arr", "boolean_arr"])
                ->default("string");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('app_settings', function (Blueprint $table) {
            $table->string("value")->change();
            $table->dropColumn("data_type");
        });
    }
}
