<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDiscount extends Model
{
    protected $table = 'product_discounts';

    public $timestamps = false;

    protected $fillable = [
        'discount_value',
        'valid_from',
        'valid_to',
        'created_at',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'discount_id');
    }
}