<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

public function register(Request $request){
    $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
    ]);

    $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
    ]);
}

public function login(Request $request)
{
    $request->validate([
        'email' => 'required',
        'password' =>'required',
    ]);
    if (!Auth::attempt($request->only('email', 'password'))) {
    return response()->json([
        'code' => 401,
        'message' => 'Invalid login details'
        ], 401);
    }

    $user = User::where('email', $request['email'])->firstOrFail();

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
    ]);
}

public function me(Request $request)
{
    return response()->json([
        'message' => $request->user() ? $request->user() : 'Please Login to get your user object'
    ]); 
}


public function logout()
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'Tokens Revoked'
        ];
    }

    // Revoke all tokens...
    // $user->tokens()->delete();
    
    // Revoke the token that was used to authenticate the current request...
    // $request->user()->currentAccessToken()->delete();
    
    // Revoke a specific token...
    // $user->tokens()->where('id', $tokenId)->delete();


}




/**
 * you may configure a scheduled tasks to delete all expired token database records that have been expired for at least 24 hours
 * $schedule->command('sanctum:prune-expired --hours=24')->daily();
 */
