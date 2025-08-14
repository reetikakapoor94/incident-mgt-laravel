<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// auth endpoints (register/login/logout using Sanctum)
Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::get('/location/{pincode}', [AuthController::class,'getLocationByPincode']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// incidents
Route::middleware('auth:sanctum')->group(function() {
    Route::get('/incidents', [IncidentController::class,'index']); // list, with optional ?incident_id=
    Route::post('/incidents', [IncidentController::class,'store']);
    Route::get('/incidents/{incident_id}', [IncidentController::class,'show']);
    Route::put('/incidents/{incident_id}', [IncidentController::class,'update']);
    Route::delete('/incidents/{incident_id}', [IncidentController::class,'destroy']);
    Route::get('/pincode/{pincode}', [IncidentController::class, 'getLocationByPincode']);

});

