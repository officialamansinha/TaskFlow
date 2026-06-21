<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register(RegisterRequest $request){
        $user = User::create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]
        );

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(
            [
                'user'=> $user,
                'token' => $token,
            ],201
        );
    }
    public function login(LoginRequest $request){
        $user = User::where('email',$request->email)->first();
        if( !$user || !Hash::check($request->password,$user->password)){
            return response()->json([
                'message'=> 'Invalid Credentials'
            ],401);
        }
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }
}
