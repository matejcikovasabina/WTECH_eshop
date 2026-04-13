<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';

    protected $primaryKey = 'product_id';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'product_id',
        'isbn',
        'publisher_id',
        'language_id',
        'binding_id',
        'year',
        'pages_num',
        'weight',
        'width',
        'height',
        'depth',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id', 'id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }

    public function binding()
    {
        return $this->belongsTo(Binding::class, 'binding_id', 'id');
    }

    public function authors()
    {
        return $this->belongsToMany(
            Author::class,
            'author_book',
            'book_id',
            'author_id'
        );
    }
}