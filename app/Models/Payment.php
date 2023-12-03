<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'm_name',
        'price',
        'quantity',
        'total',
        'delivery_address',
        'payment_type',
    ];

    // Define attributes that should be casted to arrays
    protected $casts = [
        'm_name' => 'array',
        'price' => 'array',
        'quantity' => 'array',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

}
