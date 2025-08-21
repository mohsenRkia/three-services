<?php

use App\Http\Middleware\AuthenticateJWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/products', function (Request $request) {
//    dd('products');
//})
//    ->middleware(AuthenticateJWT::class)
;
use App\Http\Controllers\ProductController;

Route::post('/products', [ProductController::class, 'create'])->middleware('api');
Route::get('/products/{id}', [ProductController::class, 'show']);
