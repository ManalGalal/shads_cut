<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait FormatTableName {

    public function getModelFromTableName($table_name) {
        // if table name is like new_cars => ["new", "cars"]
        // if table name doesn't have _ like brands it returns ["brands"];

        $separated_names = explode("_", $table_name);
        $model = "App\\Models\\";
        $counter = 0;
        foreach ($separated_names as $name) {
            //Only use singular function when the name is the last element in the array
            if ($counter === count($separated_names) - 1) {
                $name = Str::singular($name);
            }
            $model = $model . ucfirst($name);
            $counter++;
        }
        return $model;
    }
}
