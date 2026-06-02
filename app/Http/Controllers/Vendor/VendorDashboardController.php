<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Package;

class VendorDashboardController extends Controller
{
  public function index()
    {
        $userId = Auth::id(); 

        // A. Ambil ID Profile Vendor berdasarkan user_id yang sedang login
        $vendorProfile = DB::table('vendor_profiles')
            ->where('user_id', $userId)
            ->first();

        $vendorId = $vendorProfile ? $vendorProfile->id : 0;

        // B. Konversi Fungsi: getRecentOrdersForVendor()
        // B. KOnversi Fungsi: getRecentOrdersForVendor()
        $recentOrders = DB::table('bookings as b') 
            ->join('packages as p', 'b.package_id', '=', 'p.id')
            ->join('customer_profiles as cp', 'b.customer_id', '=', 'cp.id')
            ->where('p.vendor_id', $vendorId)
            ->select(
                'b.id', 
                'b.booking_status', 
                'b.payment_status', 
                'b.estimated_guests',
                'b.total_price as amount', 
                'p.package_name as package_name', 
                'cp.full_name as client_name'     
            )
            ->orderBy('b.created_at', 'desc')
            ->take(5)
            ->get()
          ->map(function ($order) {
                // Logika Cerdas: Membaca Booking Status DAN Payment Status
                if ($order->booking_status === 'pending_review') {
                    $order->statusColor = 'bg-[#fff4e5] text-[#b7791f]'; // Kuning
                    $order->statusLabel = 'Pending Review';
                } 
                elseif ($order->booking_status === 'rejected') {
                    $order->statusColor = 'bg-[#ffebee] text-[#c62828]'; // Merah
                    $order->statusLabel = 'Rejected';
                } 
                elseif ($order->booking_status === 'approved') {
                    // Jika sudah di-approve, kita cek apakah pembayarannya sudah sukses
                    if ($order->payment_status === 'success') {
                        $order->statusColor = 'bg-[#2d4a22] text-white'; // Hijau Gelap (Premium)
                        $order->statusLabel = 'Confirmed';
                    } else {
                        $order->statusColor = 'bg-[#e1f5e1] text-[#2d3e2d]'; // Hijau Muda (Belum bayar)
                        $order->statusLabel = 'Approved';
                    }
                } 
                else {
                    $order->statusColor = 'bg-gray-100 text-gray-600';
                    $order->statusLabel = 'Unknown';
                }
                
                return $order;
            });
        // C. Konversi Fungsi: getTotalOrdersForVendor()
        $totalOrdersCount = DB::table('bookings as b') // PERBAIKAN: Gunakan 'as b'
            ->join('packages as p', 'b.package_id', '=', 'p.id')
            ->where('p.vendor_id', $vendorId)
            ->count();

        // D. Konversi Fungsi: getActivePackagesCountByVendor()
        $activePackagesCount = DB::table('packages')
            ->where('vendor_id', $vendorId)
            ->where('status', 'active')
            ->count();

        // E. Hitung Otomatis Pesanan Pending Baru
        $newInquiriesCount = DB::table('bookings as b') // PERBAIKAN: Gunakan 'as b'
            ->join('packages as p', 'b.package_id', '=', 'p.id')
            ->where('p.vendor_id', $vendorId)
            ->where('b.booking_status', 'pending_review') // PERBAIKAN: Gunakan kolom dan value baru
            ->count();

        // F. Fetch Recent Reviews for Vendor
       $recentReviews = DB::table('reviews as r')
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
            ->take(3)
            ->get()
            ->map(function ($review) {
                // LOGIKA SENSOR ANONIM (MARKETPLACE STYLE)
                $name = trim($review->customer_name);
                $length = strlen($name);
                
                if ($length > 2) {
                    $first = substr($name, 0, 1);
                    $last = substr($name, -1);
                    $review->masked_name = $first . '***' . $last;
                } else {
                    $review->masked_name = 'A***'; // Fallback jika nama terlalu pendek
                }
                
                return $review;
            });

        // Lempar data ke file views
        return view('vendor.dashboard', compact(
            'recentOrders',
            'totalOrdersCount',
            'activePackagesCount',
            'newInquiriesCount',
            'recentReviews',
            'vendorProfile'
        ));
    }
    /**
     * Store vendor reply to a customer review
     */
    public function reply(Request $request, $reviewId)
    {
        // Validation
        $request->validate([
            'vendor_reply' => 'required|string|max:1000',
        ]);

        try {
            $userId = Auth::id();

            // Get vendor profile
            $vendorProfile = DB::table('vendor_profiles')
                ->where('user_id', $userId)
                ->first();

            if (!$vendorProfile) {
                return redirect()->back()->with('error', 'Vendor profile not found.');
            }

            // Get review and verify it belongs to this vendor
            $review = DB::table('reviews')
                ->where('id', $reviewId)
                ->where('vendor_id', $vendorProfile->id)
                ->first();

            if (!$review) {
                return redirect()->back()->with('error', 'Review not found or does not belong to you.');
            }

            // Update review with vendor reply
            DB::table('reviews')
                ->where('id', $reviewId)
                ->update([
                    'vendor_reply' => $request->vendor_reply,
                    'replied_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Your reply has been posted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
          
    }
}