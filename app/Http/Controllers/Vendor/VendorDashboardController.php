<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // Lempar data ke file views
        return view('vendor.dashboard', compact(
            'recentOrders',
            'totalOrdersCount',
            'activePackagesCount',
            'newInquiriesCount'
        ));
    }
}