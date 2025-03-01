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
            'product_id' => $product->id,
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
            'product_id' => $product->id,
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
        'name' => $product->discount,
        'product_id' => $product->product_id,
        'categoryname' => $product->categoryname,
        "brand_name" => $product->brand_name,
        "product_name" => $product->product_name,
        'images1' => collect($product->images1)->map(function ($image) {
            return asset($image);
        }),
        // $total = $request->quantity * $product->amount,
        // $subtotal = $request->quantity * $product->amount,
        // $subtotal = $product->discount - $subtotal,
        // $tot = $total - $subtotal,
        //  $total,
    ], 200);
}
    
public function applyCoupon(Request $request){
    $request->validate([
        'user_id' => 'required',
        'coupon_code' => 'required',
    ]);
    // dd($request);
    $cart = Cart::where('user_id', $request->user_id, $request->coupon_code)->firstOrFail();
    
    if (!$cart) {
        return response()->json([
           'message' => 'No user found' 
        ]);
    }
   
    $coupon = Coupon::where('coupon_code', $request->coupon_code)
        ->where('valid_from', '<=', now())
        ->where('valid_to', '>=', now())
        ->first();
        
    if (!$coupon) {
        return response()->json([
            'message' => 'Invalid or expired coupon'
        ], 400);
    }

    // Attach the coupon to the cart
    $cart->coupon_id = $coupon->id;
    $cart->save();
    $subtotal = 0;
    $total = 0;
    $total_paid = 0;
    return response()->json([
        'message' => 'Coupon applied successfully', 
        'cart' => $cart,
        'coupon' => $coupon,

        'subtotal' => $subtotal =  $cart->quantity * $cart->amount,
        'total' => $total = $coupon->discount * $cart->quantity,
        
        'total_paid' =>$total_paid = $subtotal / $total * 100,
        'amount' =>$total_paid,
    ],200);
}

   
public function checkout(Request $request, $id){
    $getproduct_id = Cart::find($id);
    if(!$getproduct_id) {
        return response()->json([
             'error', 'Product not found.'
        ], 404);
    }
    $reference = substr(rand(0,time()),0, 9);

  
    
    try {
        // Initialize transaction on Paystack with split details
        $response = Http::withToken('sk_test_2480c735552c0c451064507cb47a75d736c5c969')
            ->post('https://api.paystack.co/transaction/initialize', [
               
                'user_id' => $getproduct_id->id,
                'first_name' => $getproduct_id->name,
                'last_name' => $getproduct_id->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'coupon_code' => $request->coupon_code,
                'product_id' => $getproduct_id->id,
                'quantity' => $getproduct_id->quantity,
                'product_name' => $getproduct_id->product_name,
                'product_colors' => $getproduct_id->product_colors,
                'product_size' => $getproduct_id->product_size,
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
   

            // return response()->json([
            //     'data' => $result,
            // ]);

           
            $user = Auth::user();

            //  return response()->json([
            //     'data' => $user->namefor,
            // ]);
        $result = $response->json();
        $order = Order::create([
            'quantity' => $request->quantity,
            'ref_no' => substr(rand(0,time()),0, 9),
            'amount' => $request->amount,
            'user_id' => auth()->user()->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $user->email,
            'phone' => $request->phone,
            'product_id' => $getproduct_id->product_id,
           
            'reference' => $reference,
            'productname' => $getproduct_id->product_name,
            'currency' => 'NGN',
          
            'status' => 'pending',
            'images1' => collect($getproduct_id->images1)->map(function ($image) {
                        return asset($image);
            }),

            //DELEVERY DETAIL
            'delivery_address' => $request->delivery_address,
            'delivery_phone' => $request->delivery_phone,
            'delivery_state' => $request->delivery_state,
            'delivery_city' => $request->delivery_city,
            'pick_station' => $request->pick_station,
            'orderstatus' => 'Ongoing',

            
        ]);
    // $result = json_decode($response->getBody()->getContents(), true);
        return response()->json([
            'order' => $order,
            'result' => $result
        ]);

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
    // $view_mycarts = Cart::where('user_id', auth()->user()->id)->latest()->get();
    $view_mycarts = session()->get('cart');
    // return response()->json([
    //     'data' => $view_mycarts,
    // ]);
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
