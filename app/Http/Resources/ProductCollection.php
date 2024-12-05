<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($product) {
                return [
                    'product_colors' => $product->product_colors,
                    'product_size' => $product->product_size,
                    'name' => $product->name,
                    'ref_no' => $product->ref_no,
                    'images1' => asset($product->images1),
                    'quantity' => $product->quantity,
                    'purchase_price' => $product->purchase_price,
                    'amount' => $product->amount,
                    'brand_name' => $product->brand_name,
                    'categoryname' => $product->categoryname,
                    'body' => $product->body,
                    'discount' => $product->discount,
                    'created_at' => $product->created_at->toDateTimeString(),
                ];
            }),
            'total' => $this->collection->count(),
        ];
    }
}
