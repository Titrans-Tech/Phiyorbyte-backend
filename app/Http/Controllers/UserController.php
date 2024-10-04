<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
class UserController extends Controller
{
    //
    public function index(){
        $users = User::latest()->get();
        return new UserCollection($users);
        // $users = User::all(); // Get all users as a collection
        // return UserCollection::collection($users); // Return collection as a resource
    }

    
    public function suspenduser($ref_no){
        $suspend_user = User::where('ref_no', $ref_no)->first();
        $suspend_user->status = 'suspend';
        $suspend_user->save();
        return response()->json([
            'message' => 'You have been suspended'
        ], 200);
    }

    public function approveduser($ref_no){
        $approved_user = User::where('ref_no', $ref_no)->first();
        $approved_user->status = 'approved';
        $approved_user->save();
        return response()->json([
            'message' => 'You have been approved'
        ], 200);
    }

    public function update(Request $request, $ref_no){
        $edit_user = User::where('ref_no', $ref_no)->first();
        if (!$edit_user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $ref_no,
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $edit_user->update($validatedData);
         return response()->json([
            'message' => 'User updated successfully',
            'user' => $edit_user
        ], 200);

    }

    public function destroy($ref_no)
    {
        $user = User::where('ref_no', $ref_no)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }

    public function profile($ref_no)
    {
        $view_profile = User::where('ref_no', $ref_no)->first();
        if (!$view_profile) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        return new UserResource($view_profile);
      
    }


    public function myprofile($ref_no)
    {
        $view_profile = User::where('ref_no', $ref_no)->first();
        if (!$view_profile) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        return new UserResource($view_profile);
      
    }
    
    
}

    

