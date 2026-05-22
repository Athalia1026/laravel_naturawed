<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorProfile extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     * (Opsional, karena Laravel otomatis mendeteksi bentuk jamak dari nama model)
     *
     * @var string
     */
    protected $table = 'vendor_profiles';

    /**
     * Atribut yang dapat diisi melalui mass assignment.
     *
     * @var array<int, string>
     */
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
}