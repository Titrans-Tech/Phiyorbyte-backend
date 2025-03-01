<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FavouriteCollection extends ResourceCollection
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

                    'ref_no' => $view_myoders->ref_no,
                    'product_name' => $view_myoders->product_name,
                    'quantity' => $view_myoders->quantity,
                    'images1' => collect($view_myoders->images1)->map(function ($image) {
                        return asset($image);
                    }),
                    'product_colors' => $view_myoders->product_colors,
                    'amount' => $view_myoders->amount,
                    'product_size' => $view_myoders->product_size,
                    'created_at' => $view_myoders->created_at->toDateTimeString(),
                ];
            }),
            'total' => $this->collection->count(),
        ];
    }
}
