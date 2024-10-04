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
    ];

    public function product()
    {
    return $this->belongsTo(Product::class);
    }


    public function user(){
    return $this->belongsTo(User::class);
    }

}
