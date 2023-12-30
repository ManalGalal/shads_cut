<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMembershipToCoupons extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('coupons', function (Blueprint $table) {
            $table->enum("membership", ["PLAT", "GOLD", "SILVER"])->nullable();
            $table->unsignedInteger("usages_per_user")->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(["membership", "usages_per_user"]);
        });
    }
}
