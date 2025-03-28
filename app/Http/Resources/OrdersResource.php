<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_colors' => $this->product->product_colors,
              'product_size' => $this->product->product_size,
              'ref_no' => $this->ref_no,
              'images1' => collect(json_decode($this->images1, true))->map(function ($image) {
                         return asset($image);
                        }),
              'first_name' => $this->first_name,
              'last_name' => $this->last_name,
              'quantity' => $this->quantity,
              'purchase_price' => $this->purchase_price,
              'amount' => $this->amount,
              'brand_name' => $this->product->brand_name,
             
              'productname' => $this->productname,
              'body' => $this->product->body,
              'discount' => $this->discount,
              'created_at' => $this->created_at->toDateTimeString(), 
          ];
    }
}
