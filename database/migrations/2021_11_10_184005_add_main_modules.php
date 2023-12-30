<?php

use App\Models\Module;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMainModules extends Migration {

    protected $modules = [
        "users",
        "admins",
        "workers",
        "categories",
        "services",
        "product",
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
        "support_reasons",
        "support_forms",
        "roles",
        "permissions",
        "notifications"
    ];
    public function up() {
        foreach ($this->modules as $module) {
            Module::create(["name" => $module]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Module::whereIn("name", $this->modules)->delete();
    }
}
