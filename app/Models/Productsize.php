<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Productsize extends Model
{
    use HasFactory, Notifiable;

    
    protected $fillable = [
        'product_size',
        'ref_no',
        'status',
    ];

}
