<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(){

        if(Auth::check()):
            $user = Auth::user();
            $token = $user->createToken('Token Name')->accessToken;

            return response()->json([
                'user' =>$user,
                'token' =>$token
            ]);
        else:
        return [];

        endif;
    }
}
