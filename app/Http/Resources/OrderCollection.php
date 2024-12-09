<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class OrderCollection extends ResourceCollection
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
                    //'first_name' => Auth::user()->first_name,
                    //'last_name' => Auth::user()->last_name,
                    // 'order_no' => $view_myoders->product->ref_no,
                    // 'product_name' => $view_myoders->product->product_name,
                    'amount' => $view_myoders->amount,
                    'images1' => asset($view_myoders->product->images1),
                   
                    'quantity' => $view_myoders->quantity,
                    'status' => $view_myoders->status,
                    'created_at' => $view_myoders->created_at->toDateTimeString(),
                ];
            }),
            'total' => $this->collection->count(),
        ];
    }
}
