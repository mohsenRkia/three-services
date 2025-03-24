<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    public function index()
    {
        Cache::set('name','mohsen');
        dd(Cache::has('name'));
        $users = Cache::remember('users',60,function (){
           return User::all();
        });

        return response()->json($users);
    }
}
