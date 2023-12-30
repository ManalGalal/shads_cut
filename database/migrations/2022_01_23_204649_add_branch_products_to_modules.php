<?php

use App\Models\Module;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchProductsToModules extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Module::create(["name" => "branch_products"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Module::where("name", "branch_products")
            ->delete();
    }
}
