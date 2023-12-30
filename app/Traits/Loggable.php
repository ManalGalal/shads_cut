<?php

namespace App\Traits;

use App\Models\Log;
use Illuminate\Support\Arr;

trait Loggable {
    protected static function booted() {
        static::created(function ($model) {
            $attributes = $model->attributes;
            if ($model->loggable) {
                foreach ($model->loggable as $loggable) {
                    if (Arr::has($attributes, $loggable)) {
                        Log::create([
                            "table_name" => $model->getTable(),
                            "col_name" => $loggable,
                            "table_id" => $model->attributes["id"],
                            "value" => $attributes[$loggable]
                        ]);
                    }
                }
            }
            // Special cases == 
            if ($model->getTable() === "orders") {
                (new class {
                    use OrderChanges;
                })->addTax($model->attributes["id"]);
            }
        });
        static::saved(function ($model) {
            $changes = $model->changes;
            if ($model->loggable) {
                foreach ($model->loggable as $loggable) {
                    if (Arr::has($changes, $loggable)) {
                        Log::create([
                            "table_name" => $model->getTable(),
                            "col_name" => $loggable,
                            "table_id" => $model->original["id"],
                            "value" => $changes[$loggable]
                        ]);
                    }
                }
            }
            // Special cases == 
            if ($model->getTable() === "orders" && Arr::has($changes, "total_amount")) {
                (new class {
                    use OrderChanges;
                })->addTax($model->attributes["id"]);
            }
        });
    }
}
