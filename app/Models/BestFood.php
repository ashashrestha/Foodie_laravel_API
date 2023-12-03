<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BestFood extends Model
{
    use HasFactory;

    protected $table = 'best_food';

    protected $fillable = [
        'menu_id',
        'restaurant_id',
        'photo_path',
        'name',
        'm_name',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
}
