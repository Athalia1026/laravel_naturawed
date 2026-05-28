<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorBookingController extends Controller
{
    /**
     * Approve a booking request
     */
    public function approve($bookingId)
    {
        $userId = Auth::id();

        // A. Ambil ID Profile Vendor berdasarkan user_id yang sedang login
        $vendorProfile = DB::table('vendor_profiles')
            ->where('user_id', $userId)
            ->first();

        if (!$vendorProfile) {
            return back()->with('error', 'Vendor profile not found.');
        }

        $vendorId = $vendorProfile->id;

        // B. Validasi bahwa booking ini milik vendor yang sedang login
        $booking = DB::table('bookings as b')
            ->join('packages as p', 'b.package_id', '=', 'p.id')
            ->where('b.id', $bookingId)
            ->where('p.vendor_id', $vendorId)
            ->select('b.*')
            ->first();

        if (!$booking) {
            return back()->with('error', 'Booking not found or unauthorized.');
        }

        // C. Update booking status to approved
        DB::table('bookings')
            ->where('id', $bookingId)
            ->update([
                'booking_status' => 'approved',
                'updated_at' => now()
            ]);

        return back()->with('success', 'Booking approved successfully! Customer can now proceed with payment.');
    }

    /**
     * Reject a booking request
     */
    public function reject($bookingId)
    {
        $userId = Auth::id();

        // A. Ambil ID Profile Vendor berdasarkan user_id yang sedang login
        $vendorProfile = DB::table('vendor_profiles')
            ->where('user_id', $userId)
            ->first();

        if (!$vendorProfile) {
            return back()->with('error', 'Vendor profile not found.');
        }

        $vendorId = $vendorProfile->id;

        // B. Validasi bahwa booking ini milik vendor yang sedang login
        $booking = DB::table('bookings as b')
            ->join('packages as p', 'b.package_id', '=', 'p.id')
            ->where('b.id', $bookingId)
            ->where('p.vendor_id', $vendorId)
            ->select('b.*')
            ->first();

        if (!$booking) {
            return back()->with('error', 'Booking not found or unauthorized.');
        }

        // C. Update booking status to rejected
        DB::table('bookings')
            ->where('id', $bookingId)
            ->update([
                'booking_status' => 'rejected',
                'updated_at' => now()
            ]);

        return back()->with('success', 'Booking rejected successfully.');
    }
}
