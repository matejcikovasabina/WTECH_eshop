<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'product_id';
    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'description',  
        'isbn', 
        'year', 
        'pages_num',
        'image_path', 
        'is_bestseller',

        'language_id', 
        'publisher_id', 
        'cover_type_id'
    ];

    // M:N
    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_author', 'book_id', 'author_id');
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
