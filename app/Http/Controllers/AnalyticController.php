<?php

namespace App\Http\Controllers;

use App\Models\BranchModule;
use App\Models\Module;
use App\Models\User;
use App\Traits\AnalyticTraits;
use App\Traits\ModuleTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AnalyticController extends Controller {
    use AnalyticTraits, ModuleTrait;
    public function count(Request $request, Module $module) {
        [$from, $to] = $this->dateFilter($request);
        $model = $module->getModel();
        $where_query = $this->getWhereQuery($request, $module);
        if ($request->user()->isSuperAdmin()) {
            $total = $model::where("created_at", ">=", $from)
                ->where("created_at", "<=", $to)
                ->whereRaw($where_query)
                ->count();
            return response(["total" => $total]);
        }
        if (!BranchModule::where("name", $module->name)->exists()) {
            $total = 0;
            return response(["total" => $total]);
        }
        $total = $request->user()->branch->{$module->name}()
            ->where("$module->name.created_at", ">=", $from)
            ->where("$module->name.created_at", "<=", $to)
            ->whereRaw($where_query)
            ->count();
        return response(["total" => $total]);
    }
    public function graph(Request $request, Module $module) {
        [$from, $to] = $this->dateFilter($request);
        $model = $module->getModel();
        $where_query = $this->getWhereQuery($request, $module);
        if ($request->user()->isSuperAdmin()) {
            $total = $model::where("created_at", ">=", $from)
                ->where("created_at", "<=", $to)
                ->whereRaw($where_query)
                ->selectRaw("count($module->name.id) as daily_count, DATE($module->name.created_at) as date_day")
                ->groupByRaw("date_day")
                ->get();
            return response(["total" => $total]);
        }
        if (!BranchModule::where("name", $module->name)->exists()) {
            $total = 0;
            return response(["total" => $total]);
        }
        $total = $request->user()->branch->{$module->name}()
            ->where("$module->name.created_at", ">=", $from)
            ->where("$module->name.created_at", "<=", $to)
            ->whereRaw($where_query)
            ->selectRaw("count($module->name.id) as daily_count, DATE($module->name.created_at) as date_day")
            ->groupByRaw("date_day")
            ->get();
        return response(["total" => $total]);
    }
    public function countUsersOnOrderCount(Request $request, $orderCount) {
        $query_types = [
            "gt" => ">",
            "lt" => "<",
            "eq" => "="
        ];
        if (!is_numeric($orderCount)) {
            return ["total" => 0];
        }
        $type = $request->query("type") ?? "gt";
        $query =  ">";
        if (Arr::has($query_types, $type)) {
            $query = $query_types[$type];
        }
        $total = User::whereRaw("(select count(id) from orders where orders.user_id = users.id) $query $orderCount")
            ->count();
        return ["total" => $total];
    }
}
