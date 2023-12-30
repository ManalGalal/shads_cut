<?php

namespace App\Traits;

trait ModuleTrait {
    use HasRelationships;

    protected $allowed_operators = ["~", "<>", "<=", ">=", "<", "=", ">"];
    // notice the order of the above array
    // if it's not like this, for example ["<" , "<="]; 
    // every query that is like x<=value will produce something like x<"=value" only if the value is string
    // tricky bug indeed :) 

    public function generateOrderByQuery($request, $module) {
        //Example sort=-id,created_at 
        // output => "id asc, created_at desc" 
        $query = "";
        $main_query = "created_at desc";
        $columns = $module->getColumns();
        $sort = $request->query("sort");
        if (!$sort) {
            return $main_query;
        }
        $sort_elements = explode(",", $sort);
        foreach ($sort_elements as $element) {
            $first_character = $element[0]; // if first character != "-" then it should be => a col_name
            $sorting_method = "desc";
            if ($first_character == "-") {
                $sorting_method = "asc";
                $element = substr($element, 1, strlen($element));
            }

            if (!in_array($element, $columns)) {
                continue;
            }
            if ($query) {
                $query = $query . ", " . "$element $sorting_method";
                continue;
            }
            $query = "$element $sorting_method";
        }

        // if no query return by created_at
        if (!$query) {
            return $main_query;
        }
        return $query;
    }
    public function getWithArray($request, $relationships) {
        $with = $request->query("with");
        if (!$with) {
            return [];
        }
        $with = explode(",", $with);
        $withArray = [];
        foreach ($with as $relationship) {
            if (in_array($relationship, $relationships)) {
                $withArray[] = $relationship;
            }
        }
        return $withArray;
    }
    public function getWhereQuery($request, $module) {

        $main_query = "1=1";
        // expected where query to be like this 
        // => where={col_name}<operator>{value},{col_name2}<operator>{value}
        $where = $request->query("where");
        $columns = $module->getColumns();
        if (!$where) {
            return $main_query;
        }
        $conditions = explode(",", $where);
        foreach ($conditions as $condition) {
            $valid_condition = $this->isValidCondition($condition, $columns);
            if (!$valid_condition) {
                continue;
            }
            $main_query = $main_query . " and " . $valid_condition;
        }
        return $main_query;
    }
    protected function isValidCondition($condition, $columns) {
        // example brand_id=2; if we seperated using = 
        // output => array of 2 elements 
        // the first element must be a column in our model 
        $valid_condition = false;
        foreach ($this->allowed_operators as $operator) {
            $possible_query = explode($operator, $condition);
            if (count($possible_query) != 2) {
                continue;
            }
            if (in_array($possible_query[0], $columns)) {
                $valid_condition = $this->formatCondition($possible_query, $operator);
                break;
            }
        }
        return $valid_condition;
    }
    protected function formatCondition($possible_query, $operator) {
        //special condition
        if ($operator === "~") {
            return $this->generateLikeCondition($possible_query);
        }
        //if the value is numeric no need to "value";
        if (is_numeric($possible_query[1])) {
            return $possible_query[0] . $operator . $possible_query[1];
        }
        return $possible_query[0] . $operator . '"' . $possible_query[1] . '"';
    }
    protected function generateLikeCondition($possible_query) {
        return $possible_query[0] . " LIKE " . '"%' . $possible_query[1] . '%"';
    }
    /**
     * Meant for the search end-point
     * @return string
     */
    protected function generateSearchQuery($module) {
        $columns = $module->getColumns();
        $search_query = "";
        foreach ($columns as $col) {

            if (!$search_query) {
                $search_query = "$col LIKE ?";
                continue;
            }
            $search_query = $search_query . " OR $col LIKE ?";
        }
        return $search_query;
    }
}
