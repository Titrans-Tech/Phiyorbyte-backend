<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    //
    public function createproduct(Request $request){
       
        $request->validate([
            'product_name' => ['required'],
            'product_colors' => ['required'],
            'product_size' => ['required'],
            'categoryname' => ['required'],
            
            'quantity' => ['required'],
            'amount' => ['required'],
            'purchase_price' => ['required'],
            'discount' => ['required'],
            'body' => ['required'],
            'brand_name' => ['required'],
            'brand_name' => ['required'],
            'images1' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        // Handle the image upload
        if ($request->hasFile('images1')) {
            // Store the image in the 'public/products' directory
            $imagePath = $request->file('images1')->store('products', 'public');
        } else {
            $imagePath = 'noimage.jpg'; // No image uploaded
        }

        $product = Product::create([
            'images1' => $imagePath,
            'product_name' => $request->product_name,
            
            'categoryname' => $request->categoryname,
            'product_colors' => $request->product_colors,
            'product_size' => $request->product_size,
            'quantity' => $request->quantity,
            'amount' => $request->amount,
            'purchase_price' => $request->purchase_price,
            'discount' => $request->discount,
            'body' => $request->body,
            'brand_name' => $request->brand_name,
            'ref_no' => substr(rand(0,time()),0, 9),

        ]);
        // return redirect()->route('admin/products/addsecondproductphpto', ['ref_no' =>$product->ref_no]);
        //return redirect()->route('admin/products/secondpicture/'.$product->ref_no);
        return [
            'product' => $product,
        ];
    }
    public function updateproduct(Request $request, $id){
        $edit_product = Product::findOrFail($id);
        $request->validate([
            'product_name' => ['nullable'],
            'product_colors' => ['nullable'],
            'product_size' => ['nullable'],
            'quantity' => ['nullable'],
            'amount' => ['nullable'],
            'purchase_price' => ['nullable'],
            'discount' => ['nullable'],
            'body' => ['nullable'],
            'categoryname' => ['nullable'],
            
            'brand_name' => ['nullable'],
            'brand_name' => ['nullable'],
            'images1' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
       if ($request->hasFile('images1')) {
        if ($edit_product->images1) {
            Storage::disk('public')->delete($edit_product->images1);
        }
            $imagePath = $request->file('images1')->store('edit_products', 'public');
        } else {
            $imagePath = $edit_product->images1; // Keep the existing image if no new image is uploaded
        }
        $edit_product->update([
           'images1' => $imagePath,
            'product_name' => $request->product_name,
            'product_colors' => $request->product_colors,
            'product_size' => $request->product_size,
            'quantity' => $request->quantity,
            'amount' => $request->amount,
            'purchase_price' => $request->purchase_price,
            'discount' => $request->discount,
            'body' => $request->body,
            'categoryname' => $request->categoryname,
            'brand_name' => $request->brand_name,
        ]);
       
        return [
            'message' => 'Product updated successfully!',
            'product' => $edit_product,
        ];
    }

    public function viewproduct(){
        $product = Product::latest()->get();
        return new ProductCollection ($product);
    }

    public function show($ref_no){
        $product = Product::where('ref_no', $ref_no)->first();
        if (!$product) {
            return response()->json([
                'message' => 'product size not found'
            ], 404);
        }
        return new ProductResource ($product);
    }


    public function destroy($id)
    {
        $productsize = Product::where('id', $id)->first();
        if (!$productsize) {
            return response()->json([
                'message' => 'product  not found'
            ], 404);
        }
        $productsize->delete();
        return response()->json([
            'message' => 'Product  deleted successfully'
        ], 200);
    }


    public function productavailable($id){
        $available_product = Product::findOrFail($id);
        $available_product->status = 'Available';
        $available_product->save();
        return response()->json([
            'message' => 'You have set the product Available'
        ], 200);
    }

    public function productunavailable($id){
        $unavailable_product = Product::findOrFail($id);
        $unavailable_product->status = 'Unavailable';
        $unavailable_product->save();
        return response()->json([
            'message' => 'You have set the product Unavailable'
        ], 200);
    }
}
