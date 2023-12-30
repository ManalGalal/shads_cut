<?php

namespace App\Http\Controllers;

use App\Models\BranchModule;
use App\Traits\FormatTableName;
use App\Traits\HasRelationships;
use App\Traits\HttpErrors;
use App\Traits\ModuleTrait;
use Illuminate\Http\Request;

class BranchModuleController extends Controller {
    use ModuleTrait, FormatTableName, HasRelationships, HttpErrors;
    public function getAll() {
        $modules = BranchModule::all();
        return response(["modules" => $modules]);
    }
    public function getModuleDataById(Request $request, BranchModule $module, $id) {
        $original_module = $module->original_module();
        $relationships =  $this->getRelationships($original_module->getModel());
        $withArray = $this->getWithArray($request, $relationships);
        $data = $request->user()->branch->{$module->name}()->where($module->name . ".id", $id) // used_cars.id because id is ambiguous
            ->with($withArray)
            ->first();

        if (!$data) {
            return $this->NOT_FOUND(__("errors.item_not_found"));
        }

        foreach ($withArray as $relationship) {

            $population = $data[$relationship];
            if ($population) {
                // if population is a collection meaning an Array of certain class like brands collection; 

                if (get_class($population) === "Illuminate\Database\Eloquent\Collection") {
                    $population = $population->map(function ($singular) {
                        if ($singular->is_localizable) {
                            $singular->disable_localization();
                        }
                    });
                } else {
                    if ($data[$relationship]->is_localizable) {
                        $data[$relationship] = $population->disable_localization();
                    }
                }
            }
        }
        $data = $data->disable_localization();
        return response(["data" => $data->disable_localization()]);
    }
    public function getModuleData(Request $request, BranchModule $module) {
        $search_key = $request->query("search");
        $original_module = $module->original_module();
        $orderBy = $this->generateOrderByQuery($request, $original_module);
        $whereQuery = $this->getWhereQuery($request, $original_module);

        $relationships =  $this->getRelationships($original_module->getModel());
        $withArray = $this->getWithArray($request, $relationships);
        if ($search_key) {
            $search_query = $this->generateSearchQuery($original_module);
            $search_bindings = [];
            $count_of_columns = count($original_module->getColumns());

            while ($count_of_columns) {
                $search_bindings[] = '%' . $search_key . '%';
                $count_of_columns--;
            }
            $data = $request->user()->branch->{$module->name}()->orderByRaw($orderBy)
                ->whereRaw($search_query, $search_bindings)
                ->whereRaw($whereQuery)
                ->with($withArray)
                ->paginate($request->number)
                ->withQueryString();
            return response(["module" => $data]);
        }

        $data = $request->user()->branch->{$module->name}()->orderByRaw($orderBy)
            ->whereRaw($whereQuery)
            ->with($withArray)
            ->paginate($request->number)
            ->withQueryString();

        return response(["module" => $data]);
    }
}
