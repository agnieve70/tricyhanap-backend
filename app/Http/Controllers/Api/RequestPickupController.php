<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pickup;
use App\Models\RequestPickup;
use Illuminate\Http\Request;

class RequestPickupController extends Controller
{
    //
    function index(){
        $request_pickups = RequestPickup::where('status', 'waiting')->join('users', 'users.id', 'request_pickup.passenger_id')->get();
        return response()->json([
            "status" => 1,
            "message" => "Fetched successfully",
            "data" => $request_pickups,
        ], 200);
    }

    function create(Request $request){
        $request->validate([
            'current_latitude' => 'required',
            'current_longitude' => 'required',
            'to_latitude' => 'required',
            'to_longitude' => 'required',
            'amount' => 'required',
            'status' => 'required',
        ]);

        $request_pickup = new RequestPickup();
        $request_pickup->passenger_id = auth()->user()->id;
        $request_pickup->current_latitude = $request->current_latitude;
        $request_pickup->current_longitude = $request->current_longitude;
        $request_pickup->to_latitude = $request->to_latitude;
        $request_pickup->to_longitude = $request->to_longitude;
        $request_pickup->amount = $request->amount;
        $request_pickup->status = $request->status;
        $request_pickup->save();

        return response()->json([
            "status" => 1,
            "message" => "Saved successfully",
            "data" => $request_pickup,
        ], 200);
    }

    function update(Request $request){
        $request->validate([
            'status' => 'required',
            'request_pickup_id' => 'required',
            'driver_id' => 'required',
        ]);

        $request_pickup = RequestPickup::find($request->request_pickup_id);
        $request_pickup->status = !empty($request->status) ? $request->status : $request_pickup->status;
        $request_pickup->current_latitude = !empty($request->current_latitude) ? $request->current_latitude : $request_pickup->current_latitude;
        $request_pickup->current_longitude = !empty($request->current_longitude) ? $request->current_longitude : $request_pickup->current_longitude;
        $request_pickup->passenger_id = !empty($request->passenger_id) ? $request->passenger_id : $request_pickup->passenger_id;
        $request_pickup->save();

        $pickup = new Pickup();
        $pickup->request_pickup_id = $request->request_pickup_id;
        $pickup->driver_id = $request->driver_id;
        $pickup->status = 0;
        $pickup->save();

        
        return response()->json([
            "status" => 1,
            "message" => "Updated successfully",
            "request_pickup" => $request_pickup,
            "data" => $pickup,
        ], 200);
    }

    function delete($id){
        $request_pickup = RequestPickup::find($id);
        $request_pickup->delete();
        return response()->json([
            "status" => 1,
            "message" => "Request Cancelled",
            "data" => $request_pickup,
        ], 200);
    }
}
