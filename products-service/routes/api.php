<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/products', function (Request $request) {
    return $request->user();
})
    ->middleware(\App\Http\Middleware\AuthenticateJWT::class)
;
