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
        'amount' => $request->quantity * $product->amount,
        'quantity' => $request->quantity,
        $total = $request->quantity * $product->amount,
        $subtotal = $request->quantity * $product->amount,
        $subtotal = $product->discount / $subtotal * 100,
        $tot = $total - $subtotal + 500,
        $tot,
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
        return response()->json(['message' => 'Invalid or expired coupon'], 400);
    }

    // Attach the coupon to the cart
    // $coupon->coupon_id = $coupon->id;
    $coupon->save();

    return response()->json([
        'message' => 'Coupon applied successfully', 
        'cart' => $coupon
    ],200);
}

   
public function checkout(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required|string'
        ]);

        // Get the cart
        $cart = Cart::where('user_id', $request->user_id)->firstOrFail();
        
        $total = 0;
        
        // Calculate the total amount
        foreach ($cart->items as $item) {
            $total += $item->quantity * $item->product->price;
        }

        // Apply discount if a coupon is attached
        if ($cart->coupon) {
            if ($cart->coupon->type === 'fixed') {
                $total -= $cart->coupon->discount;
            } elseif ($cart->coupon->type === 'percent') {
                $total -= ($total * ($cart->coupon->discount / 100));
            }

            // Update the coupon usage count
            $cart->coupon->increment('used_count');
        }

        // Process the payment here (e.g., using Stripe, PayPal, etc.)
        // After successful payment:
        
        // Clear the cart
        $cart->items()->delete();
        $cart->delete();

        return response()->json(['message' => 'Checkout successful', 'total_paid' => $total], 200);
    }

}
