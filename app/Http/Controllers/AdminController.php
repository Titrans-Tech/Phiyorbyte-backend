<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //
    public function registeradmin(Request $request){
        $request->validate([
            'email' => ['required', 'email', 'unique:admins'],
            'password' => ['required', 'string', 'min:6'],
            // 'password_confirmation' => 'required_with:password|same:password|min:6'
        ]);

        $admin = Admin::create([
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        $token = $admin->createToken($admin->email);
        return response()->json([
            'admin' => $admin,
            'token' => $token->plainTextToken,
        ], 201);
        
    }

    public function loginadmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'message' => 'The provided credentials are not correct'
            ], 401);
        }

        $token = $admin->createToken('api-token')->plainTextToken;

        return response()->json([
            'admin' => $admin,
            'access_token' => $token,
        ]);
    }
    
    public function logoutadmin(Request $request)
    {
        $request->admin()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

}
