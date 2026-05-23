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
}
