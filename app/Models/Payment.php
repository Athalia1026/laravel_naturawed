<?php

namespace App\Models;

use App\Http\Controllers\Customer\BookingController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    /**
     * Atribut yang dapat diisi melalui mass assignment.
     */
    protected $fillable = [
        'booking_id',
        'payment_method',
        'amount',
        'payment_proof',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
        ];
    }

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * RELASI: Hubungan balik ke model Booking
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(BookingController::class, 'booking_id');
    }

    // =========================================================================
    // RE-MAPPING FUNGSI REPOSITORY LAMA ANDA
    // =========================================================================

    /**
     * Menggantikan Fungsi: updatePaymentStatus
     * Memperbarui status pembayaran dan file bukti transfer berdasarkan booking_id
     * * @param int|string $bookingId
     * @param string $imagePath
     * @param string $status
     * @return bool
     */
    public function updatePaymentStatus($bookingId, $imagePath, $status): bool
    {
        // Cari data pembayaran berdasarkan booking_id
        $payment = $this->where('booking_id', $bookingId)->first();

        if ($payment) {
            return $payment->update([
                'payment_proof' => $imagePath,
                'status'        => $status,
            ]);
        }

        return false;
    }

    /**
     * Menggantikan Fungsi: getPaymentByBookingId
     * Mengambil detail pembayaran sekaligus relasi ke booking dan package (Nested Eager Loading)
     * * @param int|string $bookingId
     * @return self|null
     */
    public function getPaymentByBookingId($bookingId)
    {
        // SQL JOIN otomatis digantikan dengan memanggil dot-relation 'booking.package'
        return $this->with(['booking.package'])
                    ->where('booking_id', $bookingId)
                    ->first();
    }
}