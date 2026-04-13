<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';

    public $timestamps = false;

    protected $fillable = [
        'street_name',
        'city',
        'zip_code',
        'state',
        'user_id',
        'address_type_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addressType()
    {
        return $this->belongsTo(AddressType::class, 'address_type_id');
    }

    public function billingOrders()
    {
        return $this->hasMany(Order::class, 'billing_address_id');
    }

    public function shippingOrders()
    {
        return $this->hasMany(Order::class, 'shipping_address_id');
    }

}