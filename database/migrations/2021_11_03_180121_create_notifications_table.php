<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->index();
            $table->string("title");
            $table->string("body");
            $table->boolean("success")->default(true);
            $table->boolean("seen")->default(false);
            $table->string("url")->nullable();
            $table->string("message_id")->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("user_id")->references("id")->onDelete("cascade")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('notifications');
    }
}
