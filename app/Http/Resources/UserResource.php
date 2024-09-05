<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'ref_no' => $this->ref_no,
            'status' => $this->status,
            'created_at' => $this->created_at->format('d, M Y'),
            'updated_at' => $this->updated_at->format('d, M Y'),
        ];
    }
}
