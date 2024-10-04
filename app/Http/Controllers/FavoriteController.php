<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function addProductTofavorite(Request $request, $id)
    {
        
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
           $favorite[$id] = [
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

       session()->put('favorite', $favorite);
       return response()->json([
  
        'message' => 'Product added to favorite', 
        'favorite_item' => $product,
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
    $favorite = favorite::where('user_id', $request->user_id, $request->coupon_code)->firstOrFail();
    if (!$favorite) {
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

    // Attach the coupon to the favorite
    // $coupon->coupon_id = $coupon->id;
    $coupon->save();

    return response()->json([
        'message' => 'Coupon applied successfully', 
        'favorite' => $coupon
    ],200);
}

   
public function checkout(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required|string'
        ]);

        // Get the favorite
        $favorite = favorite::where('user_id', $request->user_id)->firstOrFail();
        
        $total = 0;
        
        // Calculate the total amount
        foreach ($favorite->items as $item) {
            $total += $item->quantity * $item->product->price;
        }

        // Apply discount if a coupon is attached
        if ($favorite->coupon) {
            if ($favorite->coupon->type === 'fixed') {
                $total -= $favorite->coupon->discount;
            } elseif ($favorite->coupon->type === 'percent') {
                $total -= ($total * ($favorite->coupon->discount / 100));
            }

            // Update the coupon usage count
            $favorite->coupon->increment('used_count');
        }

        // Process the payment here (e.g., using Stripe, PayPal, etc.)
        // After successful payment:
        
        // Clear the favorite
        $favorite->items()->delete();
        $favorite->delete();

        return response()->json(['message' => 'Checkout successful', 'total_paid' => $total], 200);
    }

}


