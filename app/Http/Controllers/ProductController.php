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
            'name' => ['required'],
            'product_colors' => ['required'],
            'product_size' => ['required'],
            'categoryname' => ['required'],
            'quantity' => ['required'],
            'amount' => ['required'],
            'purchase_price' => ['required'],
            'discount' => ['required'],
            'body' => ['required'],
            'brand_name' => ['required'],
            'images1' => 'required|mimes:jpeg,png,jpg,gif,svg',
        ]);
       

        if ($request->hasFile('images1')){

            $file = $request['images1'];
            $filename = 'SimonJonah-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $request->file('images1')->storeAs('resourceimages1', $filename);

        }
        // $add_blog['images1'] = $path;

        $product = Product::create([
            'images1' => $path,
            'product_name' => $request->product_name,
            'name' => $request->name,
            
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
       
        return [
            'product' => $product,
        ];
    }
    public function updateproduct(Request $request, $id){
        $edit_product = Product::findOrFail($id);
        $request->validate([
            'product_name' => ['nullable'],
            'name' => ['nullable'],
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
            'images1' => 'nullable|mimes:jpeg,png,jpg,gif,svg',
        ]);
        if ($request->hasFile('images1')){

            $file = $request['images1'];
            $filename = 'SimonJonah-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $request->file('images1')->storeAs('resourceimages1', $filename);
            
            $edit_product['images1'] = $path;

        }
        $edit_product->update([
            'product_name' => $request->product_name,
            'name' => $request->name,
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

    public function viewallcategories($categoryname){
        $product = Product::where('categoryname', $categoryname)->get();
        if (!$product) {
            return response()->json([
                'message' => 'product size not found'
            ], 404);
        }
       return response()->json([
            'product' => $product,
        ], 200);
    }


    public function viewmencategory(){
        $product = Product::where('categoryname', 'Men')->get();
        if (!$product) {
            return response()->json([
                'message' => 'product size not found'
            ], 404);
        }
        
        return new ProductCollection ($product);

    //    return response()->json([
    //         'product' => $product,
    //         'images1' => asset($product->images1),
    //     ], 200);
    }

    // public function subcategoryproducts(){
    //     $product = Product::where('name', 'Sport')->get();
    //     if (!$product) {
    //         return response()->json([
    //             'message' => 'product size not found'
    //         ], 404);
    //     }
    //     return new ProductCollection ($product);

    // }

    

    public function womencategory(){
        $product = Product::where('categoryname', 'Women')->get();
        if (!$product) {
            return response()->json([
                'message' => 'product size not found'
            ], 404);
        }
        return new ProductCollection ($product);

    }

    
    public function newarrivals(){
        $product = Product::latest()->get();
        if (!$product) {
            return response()->json([
                'message' => 'product size not found'
            ], 404);
        }
        return new ProductCollection ($product);

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
        $available_product = Product::find($id);
        if ($available_product) {
            return response()->json([
                'message' => 'You have set the product Available'
            ], 200);
            $available_product->status = 'Available';
            $available_product->save();
        }else
        return response()->json([
            'message' => 'product size not found'
        ], 404);
        
        
    }

    public function productunavailable($id){
        $unavailable_product = Product::find($id);
        if (!$unavailable_product) {
            return response()->json([
                'message' => 'product size not found'
            ], 404);
        }
        $unavailable_product->status = 'Unavailable';
        $unavailable_product->save();
        return response()->json([
            'message' => 'You have set the product Unavailable'
        ], 200);
    }

    function productdetail($ref_no){
        $product_details = Product::where('ref_no', $ref_no)->first();
        return response()->json([
            'product' => $product_details
        ], 200);
    }
}
