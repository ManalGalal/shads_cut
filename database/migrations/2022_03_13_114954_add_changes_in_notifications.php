<?php

use App\Models\Notification;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChangesInNotifications extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Notification::whereRaw("1=1")->forceDelete();
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(["title_en", "title_ar", "body", "url"]);
            $table->unsignedBigInteger("notification_message_id");
            $table->unsignedBigInteger("admin_id")->nullable();
            $table->foreign("notification_message_id")->references("id")->on("notification_messages")->onDelete("cascade");
            $table->foreign("admin_id")->references("id")->on("admins")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(["notification_message_id", "admin_id"]);
            $table->dropColumn(["notification_message_id", "admin_id"]);
        });
    }
}
