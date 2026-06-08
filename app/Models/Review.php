<?php

namespace App\Models;

use App\Http\Controllers\Customer\BookingController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'vendor_id',
        'customer_id',
        'rating',
        'comment',
        'vendor_reply',
        'replied_at',
    ];

    /**
     * Relationship to Booking
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(BookingController::class);
    }

    /**
     * Relationship to VendorProfile
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(VendorProfile::class, 'vendor_id');
    }

    /**
     * Relationship to CustomerProfile
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerProfile::class, 'customer_id');
    }
}
