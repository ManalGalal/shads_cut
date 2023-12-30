<?php

use App\Models\BranchModule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminsAndProductsToBranchModule extends Migration {

    public function up() {
        BranchModule::create(["name" => "admins"]);
        BranchModule::create(["name" => "branch_products"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        BranchModule::whereIn("name", ["admins", "branch_products"])
            ->delete();
    }
}
