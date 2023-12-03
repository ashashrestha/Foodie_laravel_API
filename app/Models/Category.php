<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'c_name'
    ];

    public function restaurantCat()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class, 'c_id');
    }
}
