<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',     // Laravel Breeze default, bisa diisi dummy atau nama asli
        'email',
        'password',
        'role',     // Kolom role kustom Anda ('vendor', 'customer', 'journalist')
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Otomatis mengamankan password dengan bcrypt/argon2
        ];
    }

    // RELASI 1: Ke profil Vendor
    public function vendorProfile(): HasOne
    {
        return $this->hasOne(VendorProfile::class, 'user_id');
    }

    // RELASI 2: Ke profil Customer
    public function customerProfile(): HasOne
    {
        return $this->hasOne(CustomerProfile::class, 'user_id');
    }

    // RELASI 3: Ke profil Journalist (Tambahkan model JournalistProfile jika belum ada)
    public function journalistProfile(): HasOne
    {
        return $this->hasOne(JournalistProfile::class, 'user_id');
    }
}