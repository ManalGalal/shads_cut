<?php

namespace App\Models;

use App\Traits\FormatTableName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Module extends Model {
    use HasFactory, FormatTableName;
    protected $fillable = ["name"];


    protected static function booted() {
        /**
         * @api on model creation.. add permissions to that module instead of writting a migration
         */
        static::created(function ($module) {
            $actions = ["create", "update", "delete", "view"];
            foreach ($actions as $action) {
                if (Permission::where("name", "$action $module->name")->exists()) {
                    continue;
                }
                Permission::create(["name" => "$action $module->name", "guard_name" => "api-admin"]); //example => create brands; 
            }
        });
    }
    public function getModel() {
        return $this->getModelFromTableName($this->name);
    }
    public function getColumns() {
        return DB::getSchemaBuilder()->getColumnListing($this->name);
    }
}
