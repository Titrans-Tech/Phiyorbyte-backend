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
            'images1' =>  'required|array',
            'images1.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
       
        //  if ($request->hasfile('images1')) {
        //     foreach ($request->file('images1') as $image) {
        //         $path = $image->store('resourceimages1', 'public'); 
        //         $uploadedImages[] = $path;
        //     }
        // }


        $uploadedImages = [];
        if ($request->hasfile('images1')) {
            foreach ($request->file('images1') as $image) {
                // Define the file name and path
                $fileName = time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('resourceimages1');
    
                // Move the file to public/resourceimages1
                $image->move($destinationPath, $fileName);
    
                // Save relative path
                $uploadedImages[] = 'resourceimages1/' . $fileName;
            }
        }

        
        $product = Product::create([
            'images1' => $uploadedImages,
            // 'images1' => json_encode($path), // Store as JSON
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
        return response()->json([
           'message' => 'Product  Added successfully',
        ]);
    }

    public function firstphoto($ref_no){
        $add_product = Product::where('ref_no', $ref_no)->first();
        return view('dashboard.admin.firstphoto', compact('add_product'));
    }
    //     return redirect()->route('admin/addphoto')
    //     // return [
    //     //     'product' => $product,
    //     // ];
    // }
    public function updateproduct(Request $request, $id){
        $edit_product = Product::findOrFail($id);
        if (!$edit_product) {
            return response()->json([
                'message' => 'product  not found'
            ], 404);
        }
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
        $uploadedImages = [];
        if ($request->hasfile('images1')) {
           foreach ($request->file('images1') as $image) {
               
               $path = $image->store('resourceimages1', 'public'); 
               $uploadedImages[] = $path;
           }
       }
        $edit_product->update([
            'images1' => $uploadedImages,
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
        return new ProductCollection ($product);
    }


    public function viewmencategory(){
        $product = Product::where('categoryname', 'Men')->get();
        if (!$product) {
            return response()->json([
                'message' => 'product size not found'
            ], 404);
        }
        
        return new ProductCollection ($product);

    }



    

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
        $product = Product::latest()->take(20)->get();
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
        if (!$product_details) {
            return response()->json([
                'message' => 'product size not found'
            ], 404);
        }

        return new ProductResource($product_details);
        // return response()->json([
        //     'product' => $product_details
        // ], 200);
    }


    public function displayallproduct(){
        $product = Product::latest()->get();

        return new ProductCollection($product);
    }
}
