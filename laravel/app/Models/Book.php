<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'title', 
        'author', 
        'description', 
        'price', 
        'genre', 
        'cover_type', 
        'isbn', 
        'year', 
        'publisher', 
        'language', 
        'stock', 
        'image_path', 
        'is_bestseller'
    ];
}
