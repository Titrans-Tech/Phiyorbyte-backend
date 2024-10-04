<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;


     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'coupon_code',
        'discount',
        'user_id',
        'valid_from',
        'valid_to',
        'usage_limit',
        'used_count',
    ];
}
