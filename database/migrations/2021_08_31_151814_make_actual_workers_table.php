<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeActualWorkersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::dropIfExists('workers');
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->integer("age")->min(16);
            $table->enum("gender", ["male", "female"])->default("male");
            $table->string("profile_picture")->nullable();
            $table->string("email")->nullable();
            $table->string("phone")->unique();
            $table->string("password");
            $table->string("job_title");
            $table->unsignedBigInteger("branch_id")->index();
            $table->decimal("monthly_salary")->min(0);
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
        Schema::dropIfExists('workers');
    }
}
