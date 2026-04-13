<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discount';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
        'valid_from',
        'valid_to',
        'value',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_to' => 'date',
        'value' => 'decimal:2',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'discount_id');
    }
}