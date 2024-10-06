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
        'images2',
        'images3',
        'images4',
        'ref_no',
        'status',
    ];


    public function favorites(){
    
        return $this->hasMany(Favorite::class);
    }


    public function carts(){
    return $this->hasMany(Cart::class);
    }

    // public function coupons(){
    //     return $this->hasMany(Coupon::class);
    //     }
}
