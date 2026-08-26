<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getUsersList(){
        $users = User::all();  
        return response()->json([
            'users' => $users
        ]);
    }
}
