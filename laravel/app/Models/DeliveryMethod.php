<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryMethod extends Model
{
    protected $table = 'delivery_methods';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'delivery_method_id');
    }
}