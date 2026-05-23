<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    // 1. Mengunci nama tabel jika nama tabel di Laragon Anda bukan 'packages' (opsional)
    protected $table = 'packages';

    // 2. Mendaftarkan kolom yang diizinkan untuk memproses pengisian massal (Mass Assignment)
    protected $fillable = [
        'vendor_id',
        'category_id',
        'package_name',
        'price',
        'description',
        'features',
        'main_image',
        'status'
    ];

    /**
     * Relasi ke Tabel vendor_profiles (BelongsTo)
     * Menggantikan LEFT JOIN vendor_profiles pada kueri native Anda
     */
    public function vendorProfile()
    {
        return $this->belongsTo(VendorProfile::class, 'vendor_id');
    }

    /**
     * Relasi ke Tabel categories (BelongsTo)
     * Menggantikan LEFT JOIN categories pada kueri native Anda
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}