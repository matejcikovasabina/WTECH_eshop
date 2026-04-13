<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $table = 'authors';

    public $timestamps = false;

    protected $fillable = [
        'first_name',
        'last_name',
    ];

    public function books()
    {
        return $this->belongsToMany(
            Book::class,
            'author_book',
            'author_id',
            'book_id'
        );
    }
}