<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Tymon\JWTAuth\Facades\JWTAuth;

Route::get('/users', [UserController::class,'index'])
//    ->middleware('auth:sanctum')
;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->get('/profile', [AuthController::class, 'profile']);

Route::get('/validate-token', function (Request $request) {
    try {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json(['valid' => true, 'user_id' => $user->id]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Invalid token'], 401);
    }
});

Route::get('/test-curl',function (){
   $test = Http::get("golang-service:8000/api/gateway");
   dd($test->body());
});
