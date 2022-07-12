<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    //
    function index(Request $request){

            $request->validate([
                'name' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'role' => 'required',
                'password' => 'required|confirmed',
            ]);

            $user = new User();
            $user->email = $request->email;
            $user->name = $request->name;
            $user->role = $request->role;

            $str_rnd = Str::random(5);
            $user->remember_token = $str_rnd;
            $user->password = bcrypt($request->password);

            $user->save();

            if (!$token = auth()->attempt(["email" => $request->email, "password" => $request->password])) {
                return response()->json([
                    "errors" => [
                        "user" => 0
                    ],
                    "message" => "Cannot get token"
                ], 400);
            }

            $token = $request->user()->createToken($request->email);

            return response()->json([
                "status" => 1,
                "message" => "User registered successfully",
                "user_id" => $user->id,
                "token" => $token->plainTextToken
            ], 200);

        
    }
}
