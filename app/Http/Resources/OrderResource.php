<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_colors' => $this->product_colors,
              'product_size' => $this->product_size,
              'ref_no' => $this->ref_no,
              // 'images1' => $this->images1,
              'images1' => asset($this->images1),
            //   'images1' => asset($this->images1),
  
              'quantity' => $this->quantity,
              'purchase_price' => $this->purchase_price,
              'amount' => $this->amount,
              'brand_name' => $this->brand_name,
              'categoryname' => $this->categoryname,
              'body' => $this->body,
              'discount' => $this->discount,
              'created_at' => $this->created_at->toDateTimeString(), 
          ];
    }
}
