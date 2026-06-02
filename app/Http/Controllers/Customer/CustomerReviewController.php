<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Review;

class CustomerReviewController extends Controller
{
    /**
     * Store a review for a completed booking
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'booking_id' => 'required|integer|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        try {
            $bookingId = $request->booking_id;
            $customerId = Auth::id();

            // 1. Verify booking exists and belongs to logged-in customer
            $booking = DB::table('bookings')
                ->where('id', $bookingId)
                ->where('customer_id', function ($query) {
                    $query->select('id')
                        ->from('customer_profiles')
                        ->where('user_id', Auth::id());
                })
                ->first();

            if (!$booking) {
                return redirect()->back()->with('error', 'Booking not found or does not belong to you.');
            }

            // 2. Verify payment_status is 'success'
            if ($booking->payment_status !== 'success') {
                return redirect()->back()->with('error', 'You can only review completed bookings with successful payment.');
            }

            // 3. Check if review already exists for this booking
            $existingReview = DB::table('reviews')
                ->where('booking_id', $bookingId)
                ->first();

            if ($existingReview) {
                return redirect()->back()->with('error', 'You have already submitted a review for this booking.');
            }

            // 4. Get customer profile id
            $customerProfile = DB::table('customer_profiles')
                ->where('user_id', $customerId)
                ->first();

            if (!$customerProfile) {
                return redirect()->back()->with('error', 'Customer profile not found.');
            }

            // 5. Get vendor_id from the booking's package
            $package = DB::table('packages')
                ->where('id', $booking->package_id)
                ->first();

            if (!$package) {
                return redirect()->back()->with('error', 'Package not found.');
            }

            // 6. Create the review
            Review::create([
                'booking_id' => $bookingId,
                'vendor_id' => $package->vendor_id,
                'customer_id' => $customerProfile->id,
                'rating' => $request->rating,
                'comment' => $request->comment ?? null,
            ]);

            return redirect()->back()->with('success', 'Thank you! Your review has been submitted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
