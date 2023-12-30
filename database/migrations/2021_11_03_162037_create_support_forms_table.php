<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportFormsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('support_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("support_reason_id");
            $table->unsignedBigInteger("user_id");
            $table->string("subject")->nullable();
            $table->text("message");
            $table->enum("status", ["open", "closed", "spam"])->default("open");
            $table->timestamps();
            $table->foreign("user_id")->references("id")->onDelete("cascade")->on("users");
            $table->foreign("support_reason_id")->references("id")->onDelete("cascade")->on("support_reasons");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('support_forms');
    }
}
