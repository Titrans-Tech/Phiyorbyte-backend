<?php

namespace App\Http\Controllers;

use App\Http\Resources\FavouriteCollection;
use App\Models\Coupon;
use App\Models\Favorite;
use App\Models\Order;
use App\Models\Product;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class FavoriteController extends Controller
{
    public function addProductTofavorite(Request $request, $id)
    {
        // $cartData = $request->input('cart');
        $product = Product::find($id);
       if(!$product) {
           return response()->json([
                'error', 'Product not found.'
           ], 404);
       }

       $favorite = session()->get('favorite', []);
       if(isset($favorite[$id])) {
           $favorite[$id]['quantity']++;
       } else {
           $favorite = Favorite::create([
            'user_id' => auth()->user()->id,
            'ref_no' => substr(rand(0,time()),0, 9),
            'session_id' => $request->session_id,
            'coupon_code' => $request->coupon_code,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'product_name' => $product->product_name,
            'product_colors' => $product->product_colors,
            'product_size' => $product->product_size,
            'amount' => $product->amount,
            'discount' => $product->discount,
            'ref_no' => substr(rand(0,time()),0, 9),
            'images1' => collect($product->images1)->map(function ($image) {
            return asset($image);
            }),
           ]);
           $favorite[$id] = [
            'user_id' => auth()->user()->id,
            // 'coupon_code' => $request->coupon_code,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'product_name' => $product->product_name,
            'product_colors' => $product->product_colors,
            'product_size' => $product->product_size,
            'discount' => $product->discount,
            'amount' => $product->amount,
            'images1' => collect($product->images1)->map(function ($image) {
            return asset($image);
            }),
           ];
       }

       session()->put('favorite', $favorite);
       return response()->json([
  
        'message' => 'Product added to favorite', 
        'discount' => $product->discount,
        'amount' => $request->quantity * $product->amount,
        'quantity' => $request->quantity,
        // 'amount' => $product->discount,
        'product_id' => $request->product_id,
        'categoryname' => $product->categoryname,
        "brand_name" => $product->brand_name,
        "product_name" => $product->product_name,
        'images1' => collect($product->images1)->map(function ($image) {
            return asset($image);
        }),
        
        'total_amount' => $originalPrice = $favorite->quantity * $product->amount,
        'percentage_amount' => $discountPercentage = $product->discount,
        
        $discountAmount = ($originalPrice * $discountPercentage) / 100,
        $discountedPrice = $originalPrice - $discountAmount,
        'original_price' => $originalPrice,
        'discount_percentage' => $discountPercentage,
        'discount_amount' => $discountAmount,
        'discounted_price' => $discountedPrice
        

    ], 200);
}
    
public function applyCoupon(Request $request){
    $request->validate([
        'user_id' => 'required',
        'coupon_code' => 'required',
    ]);
    
    $favoritetotal = Favorite::where('user_id', $request->user_id)->get()
    ->sum(fn($cart) => (int) $cart->quantity * $cart->amount);

     $coupon = Coupon::where('coupon_code', $request->coupon_code)->first();
    
    if (!$favoritetotal) {
        return response()->json([
           'message' => 'No user found' 
        ]);
    }
    // // $product = Product::find($id);
    // $coupon = Coupon::where('coupon_code', $request->coupon_code)
    //     ->where('valid_from', '<=', now())
    //     ->where('valid_to', '>=', now())
    //     ->first();
        
    if (!$coupon) {
        return response()->json([
            'message' => 'Invalid or expired coupon'
        ], 400);
    }

    //  $product = Cart::find($request->user_id);
    Favorite::where('user_id', $request->user_id)->update(['coupon_code' => $request->coupon_code]);
     
    
    // Check if expired
    if (now()->lt($coupon->valid_from) || now()->gt($coupon->valid_to)) {
        return response()->json(['message' => 'Coupon is expired'], 400);
    }

    // Attach the coupon to the favorite
    // $coupon->coupon_id = $coupon->id;
    // $coupon->save();

    // Calculate discount
    $discountAmount = 0;
    if ($coupon->type === 'fixed') {
        $discountAmount = $coupon->discount;
    } elseif ($coupon->type === 'percent') {
        $discountAmount = ($favoritetotal * $coupon->discount) / 100;
    }

    
    $newTotal = max(0, $favoritetotal - $discountAmount);

    return response()->json([
        'message' => 'Coupon applied successfully', 
        'coupon' => $coupon->coupon_code,
        'original_total' => number_format($favoritetotal, 2),
        'discount' => number_format($discountAmount, 2),
        'new_total' => number_format($newTotal, 2),
        
    ],200);
}

   
public function checkout(Request $request){
      
    $reference = substr(rand(0,time()),0, 9);
    try {
        // Initialize transaction on Paystack with split details
        $response = Http::withToken('sk_test_2480c735552c0c451064507cb47a75d736c5c969')
            ->post('https://api.paystack.co/transaction/initialize', [
               
                // 'user_id' => $getproduct_id->user_id,
                'user_id' => auth()->user()->id,
                'first_name' => auth()->user()->name,
                'last_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->phone,
                // 'coupon_code' => $request->coupon_code,
                'reference' => $reference,
               
                'amount' => $request->amount,
               
                'callback_url' => route('payment.callback'),  // URL to redirect after payment
                'split' => [
                    'type' => 'percentage', // or 'flat' if you want a fixed amount
                    'subaccounts' => [
                        [
                            'subaccount' => 'ACCT_ydm5cjexrm0d88c', 
                            'share' => 50  
                        ],
                        [
                            'subaccount' => 'ACCT_whkl6chr1tbvy8j',
                            'share' => 50  
                        ]
                    ]
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

       
    $cartItems = Favorite::where('user_id', auth()->user()->id)->get();
    // $cartItems = $request->input('cart');
    // return response()->json(['message' => $cartItems], 200);
        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }
        //     DB::transaction(function () use ($cartItems) {
                foreach ($cartItems as $item) {
                    $order = Order::create([

                        'user_id' => $item->user_id,
                        'product_id' => $item->product_id,
                        'productname' => $item->product_name,
                        'cart_amount' => $item->amount,
                        'discount' => $item->discount,
                        'coupon_id' => $item->coupon_id,

                        'amount' => $request['amount'],
                        'delivery_address' => $request['delivery_address'],
                        'delivery_phone' => $request['delivery_phone'],
                        'delivery_state' => $request['delivery_state'],
                        'delivery_city' => $request['delivery_city'],
                        'pick_station' => $request['pick_station'],
                        
                        'quantity' => $item->quantity,
                        'product_colors' => $item->product_colors,
                        'product_size' => $item->product_size,
                        'reference' => $reference,
                        'email' => auth()->user()->email,
                        'user_id' => auth()->user()->id,
                        'first_name' => auth()->user()->name,
                        'last_name' => auth()->user()->name,
                        'email' => auth()->user()->email,
                        'phone' => auth()->user()->phone,
                        'status' => 'pending',
                        'images1' => json_encode($item->images1), // Convert array to JSON
                    ]);
                }
                // Delete cart items after storing in orders
               Favorite::where('user_id', $item->user_id)->delete();
            
        if ($result['status']) {
            ///Redirect to Paystack payment page
            return response([
                'message' => $result,
            ]);
            
            //return redirect($result['data']['authorization_url']);
        } else {
            return back()->with('error', 'Failed to initialize payment. Please try again.');
        }
    } 

    catch (RequestException $e) {
        throw $e; 
    }
}    


    public function myfavourites(){
        $view_myfavourites = Favorite::where('user_id', auth()->user()->id)->latest()->get();
        
        return new FavouriteCollection ($view_myfavourites);
        // return response()->json([
        //     'favourite' => $view_myfavourites,
        // ]);
    }

}


