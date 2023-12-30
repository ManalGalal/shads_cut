<?php

use App\Models\Module;
use Illuminate\Database\Migrations\Migration;

class AddWorkerSalariesToModule extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Module::create(["name" => "worker_salaries"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Module::where("name", "worker_salaries")->delete();
    }
}
