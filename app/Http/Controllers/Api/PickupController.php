<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pickup;
use App\Models\Points;
use App\Models\RequestPickup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PickupController extends Controller
{
    //
    function pickupByDriver(){
        $pickups = Pickup::select('pickup.id', 
        DB::raw('(SELECT name from users where `id` = pickup.`driver_id`) as `driver_name`'),
        DB::raw('(SELECT name from users where `id` = request_pickup.`passenger_id`) as `passenger_name`'),
        'current_latitude',
        'current_longitude',
        'to_latitude',
        'to_longitude',
        'pickup.created_at',
        'pickup.status'
        )
        ->join('request_pickup', 'request_pickup.id', 'pickup.request_pickup_id')
        ->where('driver_id', auth()->user()->id)
        ->get();
        return response()->json([
            "status" => 1,
            "message" => "Fetched successfully",
            "data" => $pickups,
        ], 200);
    }

    function pickupByPassenger(){
        $pickups = Pickup::select('pickup.id', 
        DB::raw('(SELECT name from users where `id` = pickup.`driver_id`) as `driver_name`'),
        DB::raw('(SELECT name from users where `id` = request_pickup.`passenger_id`) as `passenger_name`'),
        'current_latitude',
        'current_longitude',
        'to_latitude',
        'to_longitude',
        'pickup.created_at',
        'pickup.status'
        )
        ->join('request_pickup', 'request_pickup.id', 'pickup.request_pickup_id')
        ->where('passenger_id', auth()->user()->id)
        ->get();
        return response()->json([
            "status" => 1,
            "message" => "Fetched successfully",
            "data" => $pickups,
        ], 200);
    }

    function delete($id){
        $pickup = Pickup::find($id);
        $pickup->delete();
        return response()->json([
            "status" => 1,
            "message" => "Deleted successfully",
            "data" => $pickup,
        ], 200);
    }

    function update(Request $request){

        $request->validate([
            'status' => 'required',
            'pickup_id' => 'required',
        ]);

        if(! $pickup = Pickup::find($request->pickup_id)){
            return response()->json([
                "status" => 0,
                "message" => "Pickup Id not found.",
            ], 401);
        }
        $pickup->request_pickup_id = !empty($request->request_pickup_id) ? $request->request_pickup_id : $pickup->request_pickup_id;
        $pickup->driver_id = !empty($request->driver_id) ? $request->driver_id : $pickup->driver_id;
        $pickup->status = 1;
        $pickup->save();

        $request_pickup = RequestPickup::find($pickup->request_pickup_id);

        $points = new Points();
        $points->passenger_id = $request_pickup->passenger_id;
        $points->points = 10;
        $points->save();

        return response()->json([
            "status" => 1,
            "message" => "Updated successfully",
            "data" => $pickup,
            "points" => $points
        ], 200);
    }
}
