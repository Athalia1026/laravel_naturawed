<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Review;
use App\Models\ActivityLog; 

class CustomerReviewController extends Controller
{
    /**
     * Store a review for a completed booking (ACID-Compliant)
     */
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'booking_id' => 'required|integer|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // 🌿 ACID IMPLEMENTATION: Memulai transaksi database
        DB::beginTransaction();

        try {
            $bookingId = $request->booking_id;
            $userId = Auth::id();

            // 2. Verify booking and ownership
            $booking = DB::table('bookings')
                ->where('id', $bookingId)
                ->where('customer_id', function ($query) use ($userId) {
                    $query->select('id')
                        ->from('customer_profiles')
                        ->where('user_id', $userId);
                })
                ->first();

            if (!$booking) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Booking not found or does not belong to you.');
            }

            // 3. Verify status
            if ($booking->payment_status !== 'success') {
                DB::rollBack();
                return redirect()->back()->with('error', 'You can only review completed bookings.');
            }

            // 4. Check existing review
            $existingReview = DB::table('reviews')
                ->where('booking_id', $bookingId)
                ->exists();

            if ($existingReview) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Review already submitted.');
            }

            // 5. Get profiles and package data
            $customerProfile = DB::table('customer_profiles')->where('user_id', $userId)->first();
            $package = DB::table('packages')->where('id', $booking->package_id)->first();

            if (!$customerProfile || !$package) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Profile or package data error.');
            }

            // 6. Create the review
            $review = Review::create([
                'booking_id' => $bookingId,
                'vendor_id' => $package->vendor_id,
                'customer_id' => $customerProfile->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            // 🌟 7. Log Activity for Durability & Traceability
            ActivityLog::create([
                'user_id'    => $userId,
                'activity'   => 'Customer submitted a review for booking ID ' . $bookingId,
                'table_name' => 'reviews',
                'record_id'  => $review->id,
                'details'    => json_encode(['rating' => $request->rating]),
                'ip_address' => $request->ip()
            ]);

            // 🌿 Kunci data secara permanen (Commit)
            DB::commit();

            return redirect()->back()->with('success', 'Thank you! Your review has been submitted.');

        } catch (\Exception $e) {
            // 🌿 Batalkan transaksi jika terjadi eror (Rollback)
            DB::rollBack();
            return redirect()->back()->with('error', 'Transaction failed: ' . $e->getMessage());
        }
    }
}