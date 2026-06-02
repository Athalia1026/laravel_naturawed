<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorReviewController extends Controller
{
    /**
     * Display a paginated list of all reviews for the vendor
     */
    public function index()
    {
        $userId = Auth::id();

        // Get vendor profile
        $vendorProfile = Auth::user()->vendorProfile;
        
        if (!$vendorProfile) {
            return redirect()->back()->with('error', 'Vendor profile not found.');
        }

        $vendorId = $vendorProfile->id;

        // Fetch reviews with pagination and data masking
        $reviews = DB::table('reviews as r')
            ->join('customer_profiles as cp', 'r.customer_id', '=', 'cp.id')
            ->where('r.vendor_id', $vendorId)
            ->select(
                'r.id',
                'r.rating',
                'r.comment',
                'r.vendor_reply',
                'r.replied_at',
                'r.created_at',
                'cp.full_name as customer_name'
            )
            ->orderBy('r.created_at', 'desc')
            ->paginate(10);

        // Apply data masking to customer names
        $reviews->getCollection()->transform(function ($review) {
            $name = trim($review->customer_name);
            $length = strlen($name);
            
            if ($length > 2) {
                $first = substr($name, 0, 1);
                $last = substr($name, -1);
                $review->masked_name = $first . '***' . $last;
            } else {
                $review->masked_name = 'A***';
            }
            
            return $review;
        });

        return view('vendor.reviews', compact('reviews'));
    }
}
