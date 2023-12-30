<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDuplicatedPermissions extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $dublicates = ["create products", "update products", "delete products", "view products"];
    /**
     *  Note: each element in this array was duplicated only once.
     */
    public function up() {
        foreach ($this->dublicates as $duplicate) {
            $permission = Permission::where("name", $duplicate)
                ->first();
            $permission->delete();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        foreach ($this->dublicates as $duplicate) {
            Permission::create(["name" => $duplicate, "guard_name" => "api-admin"]);
        }
    }
}
