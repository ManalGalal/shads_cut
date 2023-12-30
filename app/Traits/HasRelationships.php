<?php

namespace App\Traits;

use ReflectionClass;
use ReflectionMethod;
use Throwable;

trait HasRelationships {
    public function getRelationships($model, $counter = 0) {
        if (!$model) {
            return [];
        }
        $methods = get_class_methods($model);
        // you have to remove Eloquonet original methods to avoid lossing DB connection
        $methods = $this->removeEloquonetOriginalMethods($methods);
        $model_instance =  new $model;
        [$methods, $relationships] = $this->getMethodsFromModelTraits($model_instance, $methods);
        foreach ($methods as $method) {
            try {
                $method_ref_method = new ReflectionMethod($model_instance, $method);
                if (
                    $model_instance->isRelation($method) && $method_ref_method->isPublic() &&
                    !count($method_ref_method->getParameters())
                ) {
                    $method_rfc = new ReflectionClass($model_instance->{$method}());
                    if (strpos($method_rfc->getName(), "Illuminate\Database\Eloquent\Relations") !== false) {
                        $relationships[] = $method;
                    }
                }
            } catch (\Throwable $th) {
                continue;
            }
        }
        // getting level 1 nested relations;
        if ($counter < 2) {
            $counter++;
            foreach ($relationships as $relationship) {
                try {
                    // whereHas to get the first model which has that relationship.
                    // Sometimes Model has the relationships but if it' returns null you can't know which class is that relationship
                    // hence you're debriving the system from pull all possible valid nested relatioships
                    $new_model_instance = $model_instance::whereHas($relationship)->first();
                    if ($new_model_instance) {
                        $new_model = $new_model_instance->{$relationship}->first();
                        if ($new_model) {
                            $new_model = get_class($new_model);
                            $new_relationships = $this->getRelationships($new_model, $counter); // don't go into infinte recursion
                            foreach ($new_relationships as $new_relationship) {
                                $relationships[] = $relationship . "." . $new_relationship; // "model.brands";
                            }
                        }
                    }
                } catch (Throwable $th) {
                    // passing "Call to a member function first() on null" issue in case the relation is null; 
                    continue;
                }
            }
        }
        return $relationships;
    }
    public function getMethodsFromModelTraits($model_instance, $all_methods) {

        $model_rc = new ReflectionClass($model_instance);
        $traits = $model_rc->getTraits();
        $relationships = []; // => [ [trait => "trait name" , methods => [] ]]

        foreach ($traits as $trait) {
            // skip localization and HasFactory traits; 
            if (strpos($trait, "localization") !== false || strpos($trait, "HasFactory") !== false) {
                continue;
            }


            $methods = $trait->getMethods();
            // if $methods is included in $all_methods remove it from it;
            foreach ($methods as $method) {
                $index = array_search($method->getName(), $all_methods);
                if ($index !== false) {
                    unset($all_methods[$index]);
                }
                if (!$model_instance->isRelation($method->getName())) {
                    continue;
                };
                try {
                    $method_rfc = new ReflectionClass($model_instance->{$method->getName()}());
                    if (strpos($method_rfc->getName(), "Illuminate\Database\Eloquent\Relations") !== false) {

                        $relationships[] = $method->getName();
                    }
                } catch (Throwable $th) {
                    continue;
                }
            }
        }
        return [$all_methods, $relationships];
    }
    public function removeEloquonetOriginalMethods($all_methods) {
        $ElQ_methods = get_class_methods("Illuminate\Database\Eloquent\Model");
        foreach ($ElQ_methods as $method) {
            $index = array_search($method, $all_methods);
            if ($index !== false) {
                unset($all_methods[$index]);
            }
        }
        return $all_methods;
    }
}
