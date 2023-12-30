<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterAllForeignKeys1 extends Migration {
    protected $single = null;
    public function up() {
        $data = DB::table("INFORMATION_SCHEMA.KEY_COLUMN_USAGE")
            ->select([
                "TABLE_NAME",
                "COLUMN_NAME",
                "CONSTRAINT_NAME",
                "REFERENCED_TABLE_NAME",
                "REFERENCED_COLUMN_NAME"
            ])
            ->whereRaw("TABLE_SCHEMA =  'shads' 
                AND REFERENCED_COLUMN_NAME IS NOT NULL 
                AND CONSTRAINT_NAME LIKE '%_foreign'")
            ->get();
        //DB::statement("use shads");
        foreach ($data as $single) {
            $this->single = $single;
            Schema::table($single->TABLE_NAME, function (Blueprint $table) {
                $table->dropForeign($this->single->CONSTRAINT_NAME);
                $table->foreign($this->single->COLUMN_NAME)->references($this->single->REFERENCED_COLUMN_NAME)
                    ->onDelete("cascade")->on($this->single->REFERENCED_TABLE_NAME);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        $data = DB::table("INFORMATION_SCHEMA.KEY_COLUMN_USAGE")
            ->select([
                "TABLE_NAME",
                "COLUMN_NAME",
                "CONSTRAINT_NAME",
                "REFERENCED_TABLE_NAME",
                "REFERENCED_COLUMN_NAME"
            ])
            ->whereRaw("TABLE_SCHEMA =  'shads' 
            AND REFERENCED_COLUMN_NAME IS NOT NULL 
            AND CONSTRAINT_NAME LIKE '%_foreign'")
            ->get();
        DB::statement("use shads");
        foreach ($data as $single) {
            $this->single = $single;
            Schema::table($single->TABLE_NAME, function (Blueprint $table) {
                $table->dropForeign($this->single->CONSTRAINT_NAME);
                $table->foreign($this->single->COLUMN_NAME)->references($this->single->REFERENCED_COLUMN_NAME)
                    ->on($this->single->REFERENCED_TABLE_NAME);
            });
        }
    }
}
