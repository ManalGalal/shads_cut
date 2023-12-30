<?php

use App\Models\BranchModule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchModules extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $modules = [
        "workers",
        "orders",
        "services",
        "paycuts",
        "additives",
        "stocks",
        "expenses",
    ];
    public function up() {
        foreach ($this->modules as $module) {
            BranchModule::create(["name" => $module]);
        }
    }


    public function down() {
        BranchModule::whereIn(["name" => $this->modules])->delete(); 
    }
}
