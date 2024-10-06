<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


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
            'images1' => $product->images1,
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
            'images1' => $product->images1,
           ];
       }

       session()->put('cart', $cart);
       return response()->json([
  
        'message' => 'Product added to cart', 
        'cart_item' => $product,
        'discount' => $product->discount,
        'amount' => $request->quantity * $product->amount,
        'quantity' => $request->quantity,
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
        
        'total_paid' =>$total_paid = $subtotal - $total,
        $total_paid,
    ],200);
}

   
public function checkout(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            // 'payment_method' => 'required|string'
        ]);

        // Get the cart
        $cart = Cart::where('user_id', $request->user_id)->firstOrFail();
       
        $subtotal = 0;
        $total = 0;
        
        if ($cart) {
            $cart = [
                // $cart->quantity * $cart->amount
                $subtotal += $cart->quantity * $cart->amount,
                $total - $cart->coupon->discount,
                $subtotal - $total,
            
            ]; // Wrap the single record in an array if necessary

            // return response()->json([
            //     'cart' => $cart,
                
            // ]); 
           
            // foreach ($cart->quantity as $quanti) {
            //     $total += $quanti->quantity * $quanti->amount;
            // }
            // $total;
        }
                
        
        // Apply discount if a coupon is attached
        // if ($cart) {
            
        //     $total -= $cart->discount;
            

        //     // Update the coupon usage count
        //     $cart->coupon->increment('used_count');
        // }

        // Process the payment here (e.g., using Stripe, PayPal, etc.)
        // After successful payment:
        
        // Clear the cart
        // $cart->cart()->delete();
        // $cart->delete();

        return response()->json([
            'message' => 'Checkout successful',
             'subtotal' => $subtotal,
             'total' => $total
            ], 200);
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
