<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;

class ChangeRefundOrderPermission extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $permission = Permission::where("name", "refund order")->first();
        if ($permission) {
            $permission->update(["name" => "create refund_order"]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        $permission = Permission::where("name", "create refund_order")->first();
        if ($permission) {
            $permission->update(["name" => "refund order"]);
        }
    }
}
