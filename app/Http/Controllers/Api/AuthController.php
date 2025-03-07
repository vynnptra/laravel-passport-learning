<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register(Request $request){
        $validate = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'password' => bcrypt($validate['password']),
        ]);

        $user = [
            'name' => $validate['name'],
            'email' => $validate['email'],
        ];

        return response()->json([
            'status' => 201,
            'message' => 'User created successfully',
            'data' => $user
            
        ],201);
    }


    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('MyAppToken')->accessToken;

            $response = [];
            $response['token'] = $token;
            $response['user'] = $user->name;
            $response['email'] = $user->email;

            return response()->json([
                'status' => 200,
                'message' => 'Auth Succesfully',
                'data' => $response
            ], 200);
        }

        return response()->json([
            'status' => 401,
            'message' => 'Faield to Login',
            'data' => null
        ], 401);
    }

    public function logout(Request $request){
        
        if (Auth::check()) {
            $request->user()->token()->revoke();
    
            return response()->json([
                'status' => 200,
                'message' => 'Logout Succesfully'
            ]);
        }

        return response()->json([
            'status' => 401,
            'message' => 'Faield to Logout'
        ]);

    }
}
