<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'brand_name',
        'categoryname',
        'quantity',
        'amount',
        'purchase_price',
        'discount',
        'body',
        'product_colors',
        'product_size',
        'name',
        'images1',
        // 'images2',
        // 'images3',
        // 'file_path',
        'ref_no',
        'status',
    ];

    protected $casts = [
        'images1' => 'array',
    ];

    public function favorites(){
    
        return $this->hasMany(Favorite::class);
    }


    public function carts(){
    return $this->hasMany(Cart::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    // In Product Model or API Controller
        // public function getImageUrlAttribute()
        // {
        //     return asset($this->images1);
        // }
}
