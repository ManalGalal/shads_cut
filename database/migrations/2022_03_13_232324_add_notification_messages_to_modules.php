<?php

use App\Models\Module;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotificationMessagesToModules extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Module::create(["name" => "notification_messages"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Module::where("name", "notification_messages")
            ->delete();
    }
}
