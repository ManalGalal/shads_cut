<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModulesToPermissions extends Migration {
    protected $modules = [
        "users",
        "workers",
        "categories",
        "services",
        "products",
        "branches",
        "cities",
        "regions",
        "paycuts",
        "additives",
        "addresses",
        "work_days",
        "coupons",
        "day_offs",
        "orders",
        "cancellation_reasons",
        "stocks",
        "expense_categories",
        "expenses",
        "banners",
        "app_settings",
        "milestones",
        "support_reasons",
        "support_forms",
        "user_devices",
        "roles"
    ];
    protected $actions = ["create", "update", "delete", "view"];
    public function up() {
        foreach ($this->modules as $module) {
            foreach ($this->actions as $action) {
                Permission::create(["name" => "$action $module", "guard_name" => "api-admin"]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        foreach ($this->modules as $module) {
            foreach ($this->actions as $action) {
                Permission::where("name", "$action $module")->delete();
            }
        }
    }
}
