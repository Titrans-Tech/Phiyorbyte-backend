<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:subcategories'],
            'categoryname' => ['required', 'string', 'max:255'],
        ]);

        $addsubcategory = Subcategory::create([
            'categoryname' => $request['categoryname'],
            'name' => $request['name'],
            'ref_no' => substr(rand(0,time()),0, 9),
        ]);
       
        return response()->json([
            'name' => $addsubcategory,
        ], 200);
    }

    public function update(Request $request, $id){
        $category = Subcategory::findOrFail($id);
        $request->validate([
            'name' => ['required', 'string'],
        ]);
        $category->name = $request->name;
        $category->update();
        return response()->json([
            'message' => 'Product  Subcategory successfully'
        ], 200);
    }

    public function destroy($id)
    {
        $category = Subcategory::where('id', $id)->first();
        if (!$category) {
            return response()->json([
                'message' => 'product size not found'
            ], 404);
        }
        $category->delete();
        return response()->json([
            'message' => 'Product Category deleted successfully'
        ], 200);
    }

    public function show(){
        $viewall_subs = Subcategory::all();
        return response()->json([
            'subcategory' => $viewall_subs
        ]);
    }


    public function viewmensubcategory(){
        $viewall_subcatoriesfor_mens = Subcategory::where('categoryname', 'Men')->latest()->get();
        return response()->json([
            'subcategory' => $viewall_subcatoriesfor_mens
        ]);
    }

    public function viewwomensubcategory(){
        $viewall_subcatoriesfor_womens = Subcategory::where('categoryname', 'Women')->latest()->get();
        return response()->json([
            'subcategory' => $viewall_subcatoriesfor_womens
        ]);
    }

    public function subcategoryproducts($name){
        $productsubscate = Subcategory::where('name', $name)->first();
        if (!$productsubscate) {
            return response()->json([
                'message' => 'product size not found'
            ], 404);
        }
        $product = Product::where('name', $name)->get();

        
        
       
        return new ProductCollection ($product);

    }



    
}
