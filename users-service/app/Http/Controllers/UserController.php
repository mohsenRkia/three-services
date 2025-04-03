<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function index()
    {
        $users = Cache::remember('users',60,function (){
           return User::all();
        });

        return response()->json($users);
    }
}
