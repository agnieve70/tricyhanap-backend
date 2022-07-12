<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PickupController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\RequestPickupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post("register", [ RegisterController::class, "index"]);
Route::post("login", [ LoginController::class, "index"]);

Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::get("request-pickup", [ RequestPickupController::class, "index"]);
    Route::post("request-pickup/create", [ RequestPickupController::class, "create"]);
    Route::post("request-pickup/update", [ RequestPickupController::class, "update"]);
    Route::delete("request-pickup/cancelled/{id}", [ RequestPickupController::class, "delete"]);
    Route::delete("pickup/cancelled/{id}", [ PickupController::class, "delete"]);
    Route::get("pickup/by-driver", [ PickupController::class, "pickupByDriver"]);
    Route::get("pickup/by-passenger", [ PickupController::class, "pickupByPassenger"]);
    Route::post("pickup/update", [ PickupController::class, "update"]);
    Route::post("report/create", [ ReportController::class, "create"]);
    Route::get("reports", [ ReportController::class, "index"]);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
