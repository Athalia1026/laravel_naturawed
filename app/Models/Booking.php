<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'user_id',      // Di Laravel database Anda, pastikan ini sinkron jika menggunakan customer_id / user_id
        'package_id',
        'booking_date',
        'wedding_date',
        'total_price',
        'status',
        'event_location', // Menyesuaikan input dari fungsi lama Anda
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'wedding_date' => 'date',
            'total_price'  => 'integer',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'booking_id');
    }

    // =========================================================================
    // ACCESSORS (Menggantikan Logika Switch-Case Warna & Rupiah Native)
    // =========================================================================

    /**
     * Otomatisasi Format Rupiah ($booking->formatted_price)
     */
    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => "Rp " . number_format($this->total_price, 0, ',', '.')
        );
    }

    /**
     * Otomatisasi Warna Badge HTML ($booking->status_color)
     */
    protected function statusColor(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match (strtolower($this->status)) {
                    'confirmed' => 'bg-[#e1f5e1] text-[#2d3e2d]',
                    'pending'   => 'bg-[#fff4e5] text-[#b7791f]',
                    'completed' => 'bg-[#e3f2fd] text-[#1976d2]',
                    'cancelled' => 'bg-[#ffebee] text-[#c62828]',
                    default     => 'bg-gray-100 text-gray-600',
                };
            }
        );
    }

    // =========================================================================
    // RE-MAPPING FUNGSI REPOSITORY LAMA ANDA KE ELOQUENT
    // =========================================================================

    /**
     * Menggantikan Fungsi 4: getCustomerBookings
     */
    public function getCustomerBookings($userId, $statusTab = 'All')
    {
        $query = $this->with(['package', 'payments'])
                      ->where('user_id', $userId);

        if ($statusTab === 'Ongoing') {
            $query->whereHas('payments', function ($q) {
                $q->whereIn('status', ['pending_verification', 'unpaid']);
            })->orWhereDoesntHave('payments');
        } elseif ($statusTab === 'Completed') {
            $query->whereHas('payments', function ($q) {
                $q->where('status', 'paid');
            });
        } elseif ($statusTab === 'Canceled') {
            $query->where('status', 'cancelled');
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Menggantikan Fungsi 5 & Fungsi Recent Orders Vendor
     */
    public function getRecentOrdersForVendor($vendorProfileId, $limit = 5)
    {
        return $this->with(['customer', 'package'])
            ->whereHas('package', function ($q) use ($vendorProfileId) {
                $q->where('vendor_profile_id', $vendorProfileId);
            })
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Menggantikan Fungsi Terakhir: getTotalOrdersForVendor
     */
    public function getTotalOrdersForVendor($vendorProfileId)
    {
        return $this->whereHas('package', function ($q) use ($vendorProfileId) {
            $q->where('vendor_profile_id', $vendorProfileId);
        })->count();
    }
}