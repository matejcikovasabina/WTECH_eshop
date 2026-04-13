<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    public $timestamps = false;

    protected $fillable = [
        'name', 
        'type', 
        'price', 
        'stock_count', 
        'category_id'
    ];

    public function book()
    {
        return $this->hasOne(Book::class, 'product_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function giftcard()
    {
        return $this->hasOne(Giftcard::class, 'product_id', 'id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_id');
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class, 'product_id');
    }

    public function wishedByUsers()
    {
        return $this->belongsToMany(User::class, 'wishlist_items', 'product_id', 'user_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function discount()
    {
        return $this->belongsTo(ProductDiscount::class, 'discount_id');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }
}