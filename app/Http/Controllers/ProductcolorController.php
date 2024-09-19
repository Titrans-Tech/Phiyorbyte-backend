<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductcolorCollection;
use App\Models\Product;
use App\Models\Productcolor;
use Illuminate\Http\Request;

class ProductcolorController extends Controller
{
    public function createcolor(Request $request){
        $request->validate([
            'product_colors' => ['required', 'string', 'max:255'],
        ]);

        $add_color = Productcolor::create([
            'product_colors' => $request['product_colors'],
            'ref_no' => substr(rand(0,time()),0, 9),
        ]);
       
        return response()->json([
            'productcolor' => $add_color,
        ], 200);
    }

    public function updatecolor(Request $request, $id){
        $edit_color = Productcolor::find($id);
        $request->validate([
            'product_colors' => ['required', 'string'],
        ]);
        $edit_color->product_colors = $request->product_colors;
        $edit_color->update();
        return response()->json([
            'message' => 'Product Color Updated successfully'
        ], 200);
    }

    public function destroycolor($id)
    {
        $productcolor = Productcolor::where('id', $id)->first();
        if (!$productcolor) {
            return response()->json([
                'message' => 'productcolor not found'
            ], 404);
        }
        $productcolor->delete();
        return response()->json([
            'message' => 'Product color deleted successfully'
        ], 200);
    }

    public function viewcolor(){
        $colors = Productcolor::latest()->get();
        return new ProductcolorCollection ($colors);
    }

    
}
