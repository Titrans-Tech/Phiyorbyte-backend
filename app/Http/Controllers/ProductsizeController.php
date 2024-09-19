<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductsizeCollection as ResourcesProductsizeCollection;
use App\Models\Productsize;
use Illuminate\Http\Request;

class ProductsizeController extends Controller
{
    //
    public function createsize(Request $request){
        $request->validate([
            'product_size' => ['required', 'string', 'max:255'],
        ]);

        $add_size = Productsize::create([
            'product_size' => $request['product_size'],
            'ref_no' => substr(rand(0,time()),0, 9),
        ]);
       
        return response()->json([
            'productsize' => $add_size,
        ], 200);
    }

    public function update(Request $request, $id){
        $edit_size = Productsize::find($id);
        $request->validate([
            'product_size' => ['required', 'string'],
        ]);
        $edit_size->product_size = $request->product_size;
        $edit_size->update();
        return response()->json([
            'message' => 'Product size Updated successfully'
        ], 200);
    }

    public function destroysize($id)
    {
        $productsize = Productsize::where('id', $id)->first();
        if (!$productsize) {
            return response()->json([
                'message' => 'product size not found'
            ], 404);
        }
        $productsize->delete();
        return response()->json([
            'message' => 'Product size deleted successfully'
        ], 200);
    }

    public function viewsize(){
        $product = Productsize::latest()->get();
        return new ResourcesProductsizeCollection ($product);
    }


}
