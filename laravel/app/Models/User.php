<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';
    
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'role_id',
        'avatar',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'created_at' => 'datetime',
        ];
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'user_id');
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class, 'user_id');
    }

    public function wishlistProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlist_items', 'user_id', 'product_id');
    }
    
    public function productReviews()
    {
        return $this->hasMany(ProductReview::class, 'user_id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }
}