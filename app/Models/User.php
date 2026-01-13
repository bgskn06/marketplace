<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'seller_status',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function shop()
    {
        return $this->hasOne(Shop::class);
    }
    public function cartItems()
    {
        return $this->hasMany(\App\Models\CartItem::class);
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    public function messages()
    {
        return $this->hasMany(\App\Models\Message::class);
    }

    protected $appends = ['seller_status_label', 'status_label'];

    public function getSellerStatusLabelAttribute()
    {
        return match ($this->seller_status) {
            0 => 'None',
            1 => 'Pending',
            2 => 'Approved',
            default => 'Unknown',
        };
    }

    public function getStatusLabelAttribute()
    {
        return $this->status == 1 ? 'Active' : 'Inactive';
    }
}
