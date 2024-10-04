<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:subcategories'],
        ]);

        $addsubcategory = Subcategory::create([
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


}
