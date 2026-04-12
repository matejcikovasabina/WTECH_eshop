<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'title', 
        // 'author', 
        'description', 
        'price', 
        'genre', 
        // 'cover_type', 
        'isbn', 
        'year', 
        // 'publisher', 
        // 'language', 
        'stock', 
        'image_path', 
        'is_bestseller',

        'language_id', 
        'publisher_id', 
        'cover_type_id'
    ];

    // M:N
    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_author');    
    }

   // 1:N
    public function coverType()
    {
        return $this->belongsTo(CoverType::class, 'cover_type_id');
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
