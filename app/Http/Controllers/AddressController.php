<?php

namespace App\Http\Controllers;

use App\Http\Requests\adminCreateAddress;
use App\Http\Requests\adminUpdateAddress;
use App\Http\Requests\createAddress;
use App\Http\Requests\updateAddress;
use App\Models\Address;
use App\Models\Location;
use App\Traits\HttpErrors;
use Illuminate\Http\Request;

class AddressController extends Controller {
    use HttpErrors;
    public function create(createAddress $request) {
        $validated = $request->validated();
        $location = Location::create($validated);
        $validated["location_id"] = $location->id;
        $validated["user_id"] = $request->user()->id;
        $address = Address::create($validated);
        return response(["message" => __("messages.address_created"), "address" => $address], 201);
    }
    public function update(updateAddress $request, Address $address) {
        $validated = $request->validated();
        $location = $address->location;
        $location->update($validated);
        $address->update($validated);
        return response(["message" => __("messages.address_updated"), "address" => $address]);
    }
    public function delete(Request $request, Address $address) {
        $user = $request->user();
        if ($address->user_id !== $user->id) {
            return $this->UNAUTHORIZED();
        }
        $address->delete();
        return response(["message" => __("messages.address_deleted")]);
    }
    public function getById(Request $request, Address $address) {
        if ($address->user_id !== $request->user()->id) {
            return $this->UNAUTHORIZED();
        }
        $address = Address::where("id", $address->id)
            ->with(["location", "region:id,name_en,name_ar,city_id", "region.city"])
            ->first();
        return response(["address" => $address]);
    }
    public function getMyAddresses(Request $request) {
        $addresses = Address::where("user_id", $request->user()->id)
            ->with(["location", "region"])
            ->get();
        return response(["addresses" => $addresses]);
    }


    /**
     * The following requests are for Admin
     */
    public function createForAdmin(adminCreateAddress $request) {
        $validated = $request->validated();
        $location = Location::create($validated);
        $validated["location_id"] = $location->id;
        $address = Address::create($validated);
        return response(["message" => __("messages.address_created"), "address" => $address], 201);
    }
    public function updateForAdmin(adminUpdateAddress $request, Address $address) {
        $validated = $request->validated();
        $location = $address->location;
        $location->update($validated);
        $address->update($validated);
        return response(["message" => __("messages.address_updated"), "address" => $address]);
    }
}
