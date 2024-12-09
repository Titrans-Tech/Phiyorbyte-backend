<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'first_name', 
        'last_name', 
        'amount', 
        'email', 
        'phone', 
        
        'product_id', 
        'franchise_id', 
        'reference', 
        'productname', 
        'images1', 
        'images2', 
        'images3', 
        'images4', 
        'images4', 
        'images5', 
        'ref_no', 
        'quantity', 
        'domain',
        'status',
        'requested_amount',
        'paid_at',
        'gateway_response',
        'channel',
        'currency',
        'ip_address',
        'time_spent',
        'errors',
        'success',
        'mobile',
        'type',
        'message',
        'time',
        'fees_split',
        'fees',
        'authorization_code',
        'bin',
        'last4',
        'exp_month',
        'exp_year',
        'card_type',
        'bank',
        'country_code',
        'brand',
        'reusable',
        'account_name',
        'signature',
        'bearer_type',
        'split_code',
        'bearer_subaccount',
        'original_share',
        'share',
        'subaccount_code',
        'name',
        'integration',
        'split_fees',
        'split_id',
        'delivery_address',
        'delivery_phone',
        'delivery_state',
        'delivery_city',
        'pick_station',
        'oderstatus',

       
        
    ];



    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    

}
