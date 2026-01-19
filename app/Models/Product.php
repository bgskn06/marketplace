<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'shop_id',
        'name',
        'category_id',
        'description',
        'price',
        'stock',
        'is_active',
        'title',
        'sku',
        'category',
        'image',
        'rating',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'imageable');
    }

    public function mainPhoto()
    {
        return $this->morphOne(Photo::class, 'imageable')->latest();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\ProductReview::class);
    }

    public function recalcRating()
    {
        $avg = $this->reviews()->avg('rating') ?: 0;
        $this->rating = round($avg, 1);
        $this->save();
        return $this->rating;
    }
}
