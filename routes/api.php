<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group( function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('/', function () {
        
        if (Auth::check()) {
            return response()->json([
    
                'status' => 200,
                'message' => 'Welcome to our API!',
    
            ], 200);
        }

        return response()->json([
            'status' => 401,
            'message' => 'Unauthorized',
        ]);
    });
});

