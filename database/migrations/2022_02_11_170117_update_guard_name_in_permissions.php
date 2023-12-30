<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGuardNameInPermissions extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Permission::where("guard_name", "!=", "api-admin")
            ->update(["guard_name" => "api-admin"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
    }
}
