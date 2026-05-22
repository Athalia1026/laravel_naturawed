<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * Atribut yang dapat diisi melalui mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'icon',
    ];

    /**
     * KUSTOMISASI TIMESTAMP:
     * Jika tabel Anda di database benar-benar tidak memiliki kolom 'updated_at',
     * aktifkan baris kode di bawah ini untuk menghindari error Eloquent.
     */
    // const UPDATED_AT = null;

    /**
     * RELASI: Hubungan One-to-Many ke Model Package
     * Satu kategori (misal: "Photography") memiliki banyak Paket Pernikahan.
     *
     * @return HasMany
     */
    public function packages(): HasMany
    {
        // Pastikan Anda memiliki model bernama Package dan kolom 'category_id' di tabel packages
        return $this->hasMany(Package::class, 'category_id');
    }
}