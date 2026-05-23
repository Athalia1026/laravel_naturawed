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
        $userId = Auth::id(); // Menggantikan $_SESSION['user_id']

        // A. Ambil ID Profile Vendor berdasarkan user_id yang sedang login
        $vendorProfile = DB::table('vendor_profiles')
            ->where('user_id', $userId)
            ->first();

        $vendorId = $vendorProfile ? $vendorProfile->id : 0;

        // B. KOnversi Fungsi: getRecentOrdersForVendor()
        // Menggabungkan tabel bookings, packages, dan customer_profiles secara dinamis
        $recentOrders = DB::table('bookings', 'b')
            ->join('packages as p', 'b.package_id', '=', 'p.id')
            ->join('customer_profiles as cp', 'b.customer_id', '=', 'cp.id')
            ->where('p.vendor_id', $vendorId)
            ->select('b.id', 'b.status', 'b.total_price as amount', 'p.package_name as package', 'cp.full_name as client')
            ->orderBy('b.created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($order) {
                // Konversi Logika Switch-Case Warna Badge Native ke Properti Objek Laravel
                switch (strtolower($order->status)) {
                    case 'confirmed':
                        $order->statusColor = 'bg-[#e1f5e1] text-[#2d3e2d]';
                        break;
                    case 'pending':
                        $order->statusColor = 'bg-[#fff4e5] text-[#b7791f]';
                        break;
                    case 'completed':
                        $order->statusColor = 'bg-[#e3f2fd] text-[#1976d2]';
                        break;
                    case 'cancelled':
                        $order->statusColor = 'bg-[#ffebee] text-[#c62828]';
                        break;
                    default:
                        $order->statusColor = 'bg-gray-100 text-gray-600';
                }
                return $order;
            });

        // C. Konversi Fungsi: getTotalOrdersForVendor()
        $totalOrdersCount = DB::table('bookings', 'b')
            ->join('packages as p', 'b.package_id', '=', 'p.id')
            ->where('p.vendor_id', $vendorId)
            ->count();

        // D. Konversi Fungsi: getActivePackagesCountByVendor()
        $activePackagesCount = DB::table('packages')
            ->where('vendor_id', $vendorId)
            ->where('status', 'active')
            ->count();

        // E. Hitung Otomatis Pesanan Pending Baru
        $newInquiriesCount = DB::table('bookings', 'b')
            ->join('packages as p', 'b.package_id', '=', 'p.id')
            ->where('p.vendor_id', $vendorId)
            ->where('b.status', 'pending')
            ->count();

        // Lempar data ke file views kustom vendor dashboard Anda
        return view('vendor.dashboard', compact(
            'recentOrders',
            'totalOrdersCount',
            'activePackagesCount',
            'newInquiriesCount'
        ));
    }
}