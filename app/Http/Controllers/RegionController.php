<?php

namespace App\Http\Controllers;

use App\Http\Requests\createRegion;
use App\Http\Requests\updateRegion;
use App\Models\Region;

class RegionController extends Controller
{
    //
    public function create(createRegion $request){
        $validated = $request->validated();
        $region = Region::create($validated);
        return response(["message" => __("messages.region_created"), "region" => $region],201);
    }
    public function update(updateRegion $request,Region $region){
        $validated = $request->validated();
        $region->update($validated);
        return response(["message" => __("messages.region_updated"),"region" => $region],202);
    }   
    public function delete(Region $region){
        $region->delete();
        return response(["message" => __("messages.region_deleted")]);
    }
    public function getById(Region $region){
        return response(["region" => $region]);
    }
    public function getAll(){
        return response(["regions" => Region::with("city")->get()]);
    }
}
