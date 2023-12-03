<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
    {
        use HasFactory;
        protected $fillable = [
            'type_id',
            'name',
            'photo_path',
            'address',
            'delivery_time'
        ];

        public function restroTypes()
    {
        return $this->belongsTo(RestoType::class, 'type_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class, 'restaurant_id');
    }

}
