<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'guest_mail',
        'status_id',
        'payment_method_id',
        'delivery_method_id',
        'billing_address_id',
        'shipping_address_id',
        'total_price',
        'created_at',
        'discount_id',
        'note',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function deliveryMethod()
    {
        return $this->belongsTo(DeliveryMethod::class, 'delivery_method_id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'order_id');
    }
    public function review()
    {
        return $this->hasOne(OrderReview::class, 'order_id');
    }
}