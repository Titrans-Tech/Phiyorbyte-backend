<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    //
    public function store(Request $request){
        $request->validate([
            // 'coupon_code' => ['required', 'string', 'max:255', 'unique:coupons'],
            'type' => ['required'],
            'discount' => ['required'],
            'valid_from' => ['required'],
            'valid_to' => ['required'],
            'usage_limit' => ['nullable'],
            
            'user_id' => 'nullable',
            
        ]);

        $add_coupon = Coupon::create([
            'type' => $request['type'],
            'coupon_code' => substr(rand(0,time()),0, 9),
            'discount' => $request['discount'],
            'valid_from' => $request['valid_from'],
            'valid_to' => $request['valid_to'],
            'usage_limit' => $request['usage_limit'],
            'user_id' => $request['used_count'],
        ]);
       
        return response()->json([
            'coupon_code' => $add_coupon,
        ], 200);
    }

    public function update(Request $request, $id){
        $add_coupon = Coupon::findOrFail($id);
        $request->validate([
            'type' => ['required', 'string'],
            'discount' => ['required', 'string'],
            'valid_from' => ['required', 'string'],
            'valid_to' => ['required', 'string'],
            'usage_limit' => ['required', 'string'],
            'user_id' => ['nullable', 'string'],
        ]);
        $add_coupon->type = $request['type'];
        $add_coupon->discount = $request['discount'];
        $add_coupon->valid_from = $request['valid_from'];
        $add_coupon->valid_to = $request['valid_to'];
        $add_coupon->usage_limit = $request['usage_limit'];
        $add_coupon->user_id = $request['used_count'];
        $add_coupon->update();
        return response()->json([
            'message' => 'Coupon updated  successfully'
        ], 200);
    }

    public function viewcoupon(){

        $view_coupons = Coupon::latest()->get();
        return response()->json([
            'coupon' => $view_coupons,
        ]);
    }

    public function destroy($id)
    {
        $addcoupon = Coupon::where('id', $id)->first();
        if (!$addcoupon) {
            return response()->json([
                'message' => 'Coupon size not found'
            ], 404);
        }
        $addcoupon->delete();
        return response()->json([
            'message' => 'Coupon Category deleted successfully'
        ], 200);
    }




}
