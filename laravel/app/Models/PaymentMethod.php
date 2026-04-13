<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'payment_methods';

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'payment_method_id');
    }
}