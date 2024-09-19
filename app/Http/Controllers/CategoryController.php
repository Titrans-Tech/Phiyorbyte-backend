<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function createcategory(Request $request){
        $request->validate([
            'categoryname' => ['required', 'string', 'max:255', 'unique:categories'],
        ]);

        $addcategory = Category::create([
            'categoryname' => $request['categoryname'],
            'ref_no' => substr(rand(0,time()),0, 9),
        ]);
       
        return response()->json([
            'categoryname' => $addcategory,
        ], 200);
    }

    public function update(Request $request, $id){
        $category = Category::findOrFail($id);
        $request->validate([
            'categoryname' => ['required', 'string'],
        ]);
        $category->categoryname = $request->categoryname;
        $category->update();
        return response()->json([
            'message' => 'Product  Category successfully'
        ], 200);
    }

    public function destroy($id)
    {
        $category = Category::where('id', $id)->first();
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
        $categories = Category::latest()->get();
        return new CategoryCollection ($categories);
    }

}
