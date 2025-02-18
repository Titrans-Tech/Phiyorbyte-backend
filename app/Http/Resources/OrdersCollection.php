<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrdersCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($view_myoders) {
                return [
                    'product_id' => $view_myoders->product_id,
                    'product_no' => $view_myoders->ref_no,
                    'first_name' => $view_myoders->first_name,
                    'last_name' => $view_myoders->last_name,
                    'order_no' => $view_myoders->product->ref_no,
                    'product_name' => $view_myoders->product->product_name,
                    'amount' => $view_myoders->amount,
                    'images1' => collect(json_decode($view_myoders->images1, true))->map(function ($image) {
                         return asset($image);
                        }),
                    'quantity' => $view_myoders->quantity,
                    'status' => $view_myoders->status,
                    'created_at' => $view_myoders->created_at->toDateTimeString(),
                ];
            }),
            'total' => $this->collection->count(),
        ];
    }
}


