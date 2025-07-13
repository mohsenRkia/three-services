<?php

namespace App\Http\Controllers;

use App\Http\Jobs\SendUserRegisteredEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        SendUserRegisteredEvent::dispatch($user);
        return response()->json(['token' => JWTAuth::fromUser($user)], 201);
    }

    public function login(Request $request)
    {
        if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(['token' => $token]);
    }

    public function profile()
    {
        $user = Cache::remember('user_' . auth()->id(), 60, function () {
            return auth()->user();
        });

        return response()->json($user);
    }
}
