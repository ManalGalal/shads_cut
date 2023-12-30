<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefundOrderToPermissions extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Permission::create(["name" => "refund order", "guard_name" => "api-admin"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Permission::where("name", "refund order")->delete();
    }
}
