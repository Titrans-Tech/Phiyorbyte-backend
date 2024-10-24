<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //

    public function registeruser(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:3'],
            'password_confirmation' => 'required_with:password|same:password|min:6'
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'address' => $request['address'],
            'phone' => $request['phone'],
            'ref_no' => substr(rand(0,time()),0, 9),
            'status' => 'approved',
            'password' => Hash::make($request['password']),
        ]);
        $token = $user->createToken($user->email);
        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken,
        ], 200);
       
    }


    public function loginuser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are not correct'
            ], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token,
        ]);
    }



    public function changelogindetails(Request $request, $ref_no){
       $user = User::where('ref_no', $ref_no)->first();
        $request->validate([
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:3'],
            'password_confirmation' => 'required_with:password|same:password|min:6'
        ]);

        $user->email = $request->email;
        $user->password = Hash::make($request['password']);
        $user->update();
       
        $token = $user->createToken($user->email);
        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken,
        ], 200);
       
    }
    
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
