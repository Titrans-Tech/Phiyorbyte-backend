<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'product_colors',
        'product_size',
        'product_name',
        'amount',
        'images1',
        'session_id',
        'coupon_code',
        'discount',
        'ref_no',
        'session_id',
    ];



    protected $casts = [
        'images1' => 'array',
    ];
    public function product()
    {
    return $this->belongsTo(Product::class);
    }


    public function user(){
    return $this->belongsTo(User::class);
    }

}
