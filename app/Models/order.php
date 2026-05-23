<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Mengarahkan model ke tabel database bookings Anda
    protected $table = 'bookings';

    protected $fillable = [
        'customer_id',
        'package_id',
        'event_date',
        'event_location',
        'notes',
        'total_price',
        'status',
    ];

    // Relasi mengambil data paket
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}