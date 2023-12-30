<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("branch_id");
            $table->string("name");
            $table->string("email")->unique();
            $table->string("phone")->unique();
            $table->string("password");
            $table->enum("role", ["super", "normal"])->default("normal");
            $table->string("profile_picture")->nullable();
            $table->decimal("monthly_salary")->default(0)->min(0);
            $table->rememberToken();
            $table->timestamps();
            $table->foreign("branch_id")->references("id")->on("branches");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('admins');
    }
}
