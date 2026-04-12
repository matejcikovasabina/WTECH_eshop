<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 
        'type', 
        'price', 
        'stock_count', 
        'category_id'
    ];

    public function book()
    {
        return $this->hasOne(Book::class, 'product_id');
    }
}