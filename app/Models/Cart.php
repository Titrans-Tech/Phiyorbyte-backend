<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    // protected $casts = [
    //     'quantity' => 'array', // It will automatically serialize/deserialize
    //     'amount' => 'array', // It will automatically serialize/deserialize
    // ];

    public function setquantityAttribute($value)
{
    $this->attributes['quantity'] = json_encode($value);
}

public function getquantityAttribute($value)
{
    return json_decode($value, true);
}
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'product_colors',
        'product_size',
        'product_name',
        'amount',
        'coupon_id',
        'images1',
    ];


    protected $casts = [
        'images1' => 'array',
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];
    

    public function product()
    {
    return $this->belongsTo(Product::class);
    }

    public function coupon()
    {
    return $this->belongsTo(Coupon::class);
    }
    public function user(){
    return $this->belongsTo(User::class);
    }
}
