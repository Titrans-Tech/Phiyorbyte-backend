<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CartCollection extends ResourceCollection
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
                    'id' => $view_myoders->id,
                    'user_id' => $view_myoders->user_id,
                    'product_name' => $view_myoders->product_name,
                    'quantity' => $view_myoders->quantity,
                    'discount' => $view_myoders->discount,
                    'images1' => collect($view_myoders->images1)->map(function ($image) {
                        return asset($image);
                    }),

                    
                    'product_colors' => $view_myoders->product_colors,
                    'amount' => $view_myoders->amount,
                    
                    'product_size' => $view_myoders->product_size,


                    'total' => $view_myoders->quantity * $view_myoders->amount,
                    'created_at' => $view_myoders->created_at->toDateTimeString(),
                ];
            }),
            'total' => $this->collection->count(),
        ];
    }
}
