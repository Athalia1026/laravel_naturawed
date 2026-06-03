<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'business_name',
        'address',
        'instagram',
        'website',
        'cover_image',
        'profile_image',
        'team_image',
        'team_description',
        'bio',
    ];

    /**
     * Hubungan timbal balik ke model User (Laravel Breeze).
     * Setiap profil vendor dimiliki oleh satu User.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function packages()
    {
        return $this->hasMany(Package::class, 'vendor_id');
    }
    /**
     * Relasi ke model Review.
     * Satu profil vendor bisa memiliki banyak review dari customer.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'vendor_id');
    }
}
