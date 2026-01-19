<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'logo',
        'rating',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function followers()
    {
        return $this->hasMany(\App\Models\ShopFollow::class);
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\ShopReview::class);
    }

    public function followersCount()
    {
        return $this->followers()->count();
    }
}
