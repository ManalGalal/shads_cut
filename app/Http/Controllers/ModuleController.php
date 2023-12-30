<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\User;
use App\Traits\FormatTableName;
use App\Traits\HasRelationships;
use App\Traits\HttpErrors;
use App\Traits\ModuleTrait;
use Illuminate\Http\Request;

class ModuleController extends Controller {
    use ModuleTrait, FormatTableName, HasRelationships, HttpErrors;
    public function getAll() {
        $modules = Module::all();
        return response(["modules" => $modules]);
    }

    public function getModuleInfo(Module $module) {
        $columns = $module->getColumns();
        $model = $module->getModel();

        return response(["module" => ["columns" => $columns, "relations" => $this->getRelationships($model)]]);
    }
    public function getModuleDataById(Request $request, Module $module, $id) {
        $model = $module->getModel();
        $relationships =  $this->getRelationships($model);
        $withArray = $this->getWithArray($request, $relationships);
        $data = $model::where("id", $id)
            ->with($withArray)
            ->first();

        if (!$data) {
            return $this->NOT_FOUND(__("errors.item_not_found"));
        }
        if ($data->is_localizable) {
            $data = $data->disable_localization();
        }
        return response(["data" => $data]);
    }
    public function getModuleData(Request $request, Module $module) {
        $search_key = $request->query("search");
        $orderBy = $this->generateOrderByQuery($request, $module);
        $whereQuery = $this->getWhereQuery($request, $module);
        $model = $module->getModel(); // => App\\Models \\Brands;

        $relationships =  $this->getRelationships($model);
        $withArray = $this->getWithArray($request, $relationships);

        if ($search_key) {
            $search_query = $this->generateSearchQuery($module, $search_key);
            $search_bindings = [];
            $count_of_columns = count($module->getColumns());

            while ($count_of_columns) {
                $search_bindings[] = '%' . $search_key . '%';
                $count_of_columns--;
            }
            $data = $model::whereRaw($search_query, $search_bindings)
                ->whereRaw($whereQuery)
                ->with($withArray)
                ->paginate($request->number)
                ->withQueryString();
            return response(["module" => $data]);
        }

        $data = $model::orderByRaw($orderBy)
            ->whereRaw($whereQuery)
            ->with($withArray)
            ->paginate($request->number)
            ->withQueryString();

        return response(["module" => $data]);
    }
    
    public function getDeleted(Request $request, Module $module) {
        $whereQuery = $this->getWhereQuery($request, $module);
        $model = $module->getModel();
        $deleted = $model::onlyTrashed()
            ->whereRaw($whereQuery)
            ->paginate($request->number)
            ->withQueryString();
        return response(["deleted" => $deleted]);
    }
    public function restoreDeletedById(Module $module, $id) {
        $model = $module->getModel();
        $restored = $model::where("id", $id)
            ->restore();
        return response(["restored" => $restored]);
    }
    public function delete(Module $module, $id) {
        $model = $module->getModel();
        $model::where("id", $id)
            ->delete();
        return response(["message" => __("messages.model_deleted")]);
    }
    public function hardDelete(Module $module, $id) {
        $model = $module->getModel();
        $model::where("id", $id)
            ->forceDelete();
        return response(["message" => __("messages.model_deleted")]);
    }
}
