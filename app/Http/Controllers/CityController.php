<?php

namespace App\Http\Controllers;

use App\Http\Requests\createCity;
use App\Http\Requests\updateCity;
use App\Models\City;

class CityController extends Controller {
    //
    public function create(createCity $request) {
        $validated = $request->validated();
        $city = City::create($validated);
        return response(["message" => __("messages.city_created")], 201);
    }
    public function update(updateCity $request, City $city) {
        $validated = $request->validated();
        $city->update($validated);
        return response(["message" => __("messages.city_updated")], 201);
    }
    public function delete(City $city) {
        $city->delete();
        return response(["message" => __("messages.city_removed")]);
    }
    public function getById(City $city) {
        $city = City::where("id", $city->id)
            ->select(["id", "name_en", "name_ar"])
            ->with("regions:id,name_en,name_ar,city_id")
            ->first();
        return response(["city" => $city]);
    }
    public function getAll() {
        $cities = City::select(["id", "name_en", "name_ar"])
            ->with("regions:id,name_en,name_ar,city_id")
            ->get();
        return response(["cities" => $cities]);
    }
}
