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
        'images1',
        'images2',
        'images3',
        'images4',
        'ref_no',
        'status',
    ];
}
