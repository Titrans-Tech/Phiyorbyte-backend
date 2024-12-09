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
            'data' => $this->collection->transform(function ($view_mycarts) {
                return [
                    'user_id' => $view_mycarts->user_id,
                    'product_name' => $view_mycarts->product_name,
                    'quantity' => $view_mycarts->quantity,
                    'images1' => asset($view_mycarts->images1),
                    'product_colors' => $view_mycarts->product_colors,
                    'amount' => $view_mycarts->amount,
                    'product_size' => $view_mycarts->product_size,
                    'created_at' => $view_mycarts->created_at->toDateTimeString(),
                ];
            }),
            'total' => $this->collection->count(),
        ];
    }
}
