<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePageContent extends Model
{
    use HasFactory;

    protected $table = 'home_page_contents'; 

    protected $fillable = [
        'container_number',
        'image_path', 
        'title', 
        'text'
    ];
}
