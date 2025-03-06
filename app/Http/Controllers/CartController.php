<?php

namespace App\Http\Controllers;
// use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CartCollection;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Sale;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    //

    public function addProductToCart(Request $request, $id)
    {
        $product = Product::find($id);
       if(!$product) {
           return response()->json([
                'error', 'Product not found.'
           ], 404);
       }

       $cart = session()->get('cart', []);

       // If the product is already in the cart, just update the quantity
       if(isset($cart[$id])) {
           $cart[$id]['quantity']++;
       } else {
           // Add the product to the cart
           $cart = Cart::create([
            'user_id' => $request->user_id,
            'coupon_code' => $request->coupon_code,
            'product_id' => $request->id,
            'quantity' => $request->quantity,
            'product_name' => $product->product_name,
            'product_colors' => $product->product_colors,
            'product_size' => $product->product_size,
            'amount' => $product->amount,
            'images1' => collect($product->images1)->map(function ($image) {
                return asset($image);
            }),
           ]);
           $cart[$id] = [
            'user_id' => $request->user_id,
            'coupon_code' => $request->coupon_code,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'product_name' => $product->product_name,
            'product_colors' => $product->product_colors,
            'product_size' => $product->product_size,
            'amount' => $product->amount,
            // 'images1' => $product->images1,
        //    'images1' => json_encode($request->images1),
           ];
       }

       session()->put('cart', $cart);
       return response()->json([
  
        'message' => 'Product added to cart', 
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
        

        $total = $cart->quantity * $product->amount,
        $subtotal = $product->discount,
        $tot = $total - $subtotal,
        $tot,
    ], 200);
}
    
public function applyCoupon(Request $request){
    $request->validate([
        'user_id' => 'required',
        'coupon_code' => 'required',
    ]);
    
    $cartTotal = Cart::where('user_id', $request->user_id)->get()
    ->sum(fn($cart) => (int) $cart->quantity * $cart->amount);

     $coupon = Coupon::where('coupon_code', $request->coupon_code)->first();

     if (!$coupon) {
         return response()->json(['message' => 'Coupon is expired or invalid'], 400);
     }
    // Check if expired
    if (now()->lt($coupon->valid_from) || now()->gt($coupon->valid_to)) {
        return response()->json(['message' => 'Coupon is expired'], 400);
    }

    // Calculate discount
    $discountAmount = 0;
    if ($coupon->type === 'fixed') {
        $discountAmount = $coupon->discount;
    } elseif ($coupon->type === 'percent') {
        $discountAmount = ($cartTotal * $coupon->discount) / 100;
    }

    
    $newTotal = max(0, $cartTotal - $discountAmount);

    return response()->json([
        'original_total' => number_format($cartTotal, 2),
        'discount' => number_format($discountAmount, 2),
        'new_total' => number_format($newTotal, 2),
        'message' => 'Coupon applied successfully!',
    ]);
}

   
public function checkout(Request $request){
    $getproduct_id = Cart::where('user_id', auth()->user()->id)->get();
    // $getproduct_id = session()->get('cart', []);
    // if(!$getproduct_id) {
    //     return response()->json([
    //          'error', 'Cart is empty.',
    //     ], 404);
    // }
    $reference = substr(rand(0,time()),0, 9);

  
    
    try {
        // Initialize transaction on Paystack with split details
       
        $response = Http::withToken('sk_test_2480c735552c0c451064507cb47a75d736c5c969')
            ->post('https://api.paystack.co/transaction/initialize', [
               
                // 'user_id' => $getproduct_id->user_id,
                'first_name' => auth()->user()->name,
                'last_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->phone,
                'coupon_code' => $request->coupon_code,
                // 'cart_amount' => $getproduct_id->cart_amount,

                // 'product_id' => $cart->product_id,
                // 'quantity' => $getproduct_id->quantity,
                // 'product_name' => $getproduct_id->product_name,
                // 'product_colors' => $getproduct_id->product_colors,
                // 'product_size' => $getproduct_id->product_size,
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
   

        
            $userId = Auth::user();

           
        $cartItems = Cart::where('user_id', auth()->user()->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }
            DB::transaction(function () use ($cartItems, $userId) {
                foreach ($cartItems as $item) {
                    $order = Order::create([
                        'user_id' => $item->user_id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product_name,
                        'amount' => $item->amount,
                        'quantity' => $item->quantity,
                        'product_colors' => $item->product_colors,
                        'product_size' => $item->product_size,

                        'email' => $userId['email'],
                        'phone' => $userId['phone'],
                        'first_name' => $userId['first_name'],
                        'last_name' => $userId['last_name'],
                        'user_id' => $userId['user_id'],
                        'status' => 'pending',
                        // 'images1' => $item->images1, // This is cast as JSON in the model
                    ]);
                }
                // Delete cart items after storing in orders
                Cart::where('user_id', $userId)->delete();
            });
        
            return response()->json(['message' => 'Order placed successfully. Cart cleared!'], 200);
        
        // Check if the payment initialization was successful
        if ($order['status']) {
            // Redirect to Paystack payment page
            return redirect($order['data']['authorization_url']);
        } else {
            return back()->with('error', 'Failed to initialize payment. Please try again.');
        }
    } 

    catch (RequestException $e) {
        throw $e; 
    }
}        


public function mycartproducts(){
    $view_mycarts = Cart::where('user_id', auth()->user()->id)->latest()->get();
    
    
   return new CartCollection ($view_mycarts);

 }

    public function remove($id)
    {
        $cart = session()->get('cart');

        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            $cart = Cart::where('id', $id)->delete();
        }

        return response()->json([
            'message', 'Product removed from cart.'
        ], 200);
    }
}
