<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Pdf;

class AnalyticsController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Ambil data profil vendor yang sedang login
        $vendorProfile = DB::table('vendor_profiles')->where('user_id', $userId)->first();

        if (!$vendorProfile) {
            return redirect()->route('dashboard')->with('error', 'Please complete your profile first.');
        }

        $currentYear = Carbon::now()->year;

        // --- 1. DATA REVENUE TREND (VERSI FIXED - SINKRON DENGAN STATUS 'PAID') ---
        $revenueLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $revenueData = array_fill(0, 12, 0);

        $currentYear = \Carbon\Carbon::now()->year; // Tahun 2026
        $startOfYear = "$currentYear-01-01 00:00:00";
        $endOfYear = "$currentYear-12-31 23:59:59";

        $monthlyRevenues = DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('packages', 'bookings.package_id', '=', 'packages.id')
            ->where('packages.vendor_id', $vendorProfile->id)

            // 🌿 PERBAIKAN UTAMA: Mengubah kata kunci filter dari 'success' menjadi 'paid'
            ->where('payments.status', 'LIKE', 'paid')

            ->whereBetween('payments.created_at', [$startOfYear, $endOfYear])
            ->select(
                DB::raw('MONTH(payments.created_at) as month'),
                DB::raw('SUM(payments.amount) as total')
            )
            ->groupBy(DB::raw('MONTH(payments.created_at)'))
            ->get();

        foreach ($monthlyRevenues as $revenue) {
            if ($revenue->month >= 1 && $revenue->month <= 12) {
                $revenueData[$revenue->month - 1] = (float) $revenue->total;
            }
        }


        // --- 2. DATA POPULAR PACKAGES (Hanya Menghitung Booking yang Statusnya Sudah Selesai) ---
        $popularPackages = DB::table('packages')
            // Mengubah leftJoin menjadi join agar paket yang belum pernah di-book tidak mengotori grafik analitik sukses
            ->join('bookings', 'packages.id', '=', 'bookings.package_id')
            ->where('packages.vendor_id', $vendorProfile->id)

            // 🌿 BARU: Filter ketat agar hanya menghitung transaksi yang sukses/approved
            // (Jika string penanda selesai di DB Anda adalah 'success', ganti 'approved' menjadi 'success')
            ->where('bookings.booking_status', '=', 'approved')

            ->select(
                'packages.package_name',
                DB::raw('COUNT(bookings.id) as total_booked')
            )
            ->groupBy('packages.id', 'packages.package_name')
            ->orderBy('total_booked', 'desc')
            ->take(5)
            ->get();

        $packageLabels = [];
        $packageData = [];

        foreach ($popularPackages as $pkg) {
            $packageLabels[] = $pkg->package_name;
            $packageData[] = (int) $pkg->total_booked;
        }

        // --- 3. DATA STATUS BOOKING (Riil Mengelompokkan Status dari Tabel Bookings) ---
        $bookingStatuses = DB::table('bookings')
            ->join('packages', 'bookings.package_id', '=', 'packages.id')
            ->where('packages.vendor_id', $vendorProfile->id)
            ->select(
                'bookings.booking_status',
                DB::raw('COUNT(bookings.id) as count')
            )
            ->groupBy('bookings.booking_status')
            ->get();

        // Siapkan struktur mapping sesuai urutan labels: Pending, Success, Cancelled
        $statusCounts = ['pending_review' => 0, 'approved' => 0, 'rejected' => 0]; // Sesuaikan dengan value ENUM asli Anda

        foreach ($bookingStatuses as $status) {
            if (array_key_exists($status->booking_status, $statusCounts)) {
                $statusCounts[$status->booking_status] = $status->count;
            }
        }

        $statusLabels = ['Pending Review', 'Approved', 'Rejected'];
        $statusData = [
            $statusCounts['pending_review'],
            $statusCounts['approved'],
            $statusCounts['rejected']
        ];


        // 2. Kirim semua data riil beserta $vendorProfile ke view agar sidebar tidak eror
        return view('vendor.analytics', compact(
            'vendorProfile',
            'revenueLabels',
            'revenueData',
            'packageLabels',
            'packageData',
            'statusLabels',
            'statusData'
        ));
    }
    // 🌿 PASTIKAN BERIKUT SUDAH DI-IMPORT DI BAGIAN ATAS CONTROLLER:
// use Pdf; // Jika menggunakan alias facade, atau panggil full namespace di bawah ini

    public function exportPdf()
    {
        $userId = \Illuminate\Support\Facades\Auth::id();
        $vendorProfile = \Illuminate\Support\Facades\DB::table('vendor_profiles')->where('user_id', $userId)->first();

        if (!$vendorProfile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        $currentYear = \Carbon\Carbon::now()->year;
        $startOfYear = "$currentYear-01-01 00:00:00";
        $endOfYear = "$currentYear-12-31 23:59:59";

        // Query Mengambil Seluruh Transaksi Selesai / Paid Milik Vendor Ini
        $transactions = \Illuminate\Support\Facades\DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('packages', 'bookings.package_id', '=', 'packages.id')
            ->join('users', 'bookings.customer_id', '=', 'users.id')
            ->where('packages.vendor_id', $vendorProfile->id)
            ->where('payments.status', 'LIKE', 'paid') // Hanya yang berstatus paid
            ->whereBetween('payments.created_at', [$startOfYear, $endOfYear])
            ->select(
                'payments.booking_id',
                'payments.amount',
                'payments.status as payment_status',
                'bookings.event_date',
                'users.name as customer_name', // Ambil nama customer jika ada di kolom bookings Anda
                'packages.package_name'
            )
            ->orderBy('payments.created_at', 'desc')
            ->get();

        // Hitung Ringkasan Finansial Agregat Otomatis
        $totalEvents = $transactions->count();
        $totalRevenue = $transactions->sum('amount');

        // Load View dan Konversi ke PDF Menggunakan Facade DOMPDF instan
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('vendor.report_pdf', compact('vendorProfile', 'transactions', 'totalEvents', 'totalRevenue'));

        // Set Ukuran Kertas Potret A4 Profesional
        $pdf->setPaper('a4', 'portrait');

        // Unduh File Secara Otomatis dengan Penamaan Dinamis Berbasis Waktu
        return $pdf->download('NaturaWed-Report-' . \Carbon\Carbon::now()->format('Y-m-d') . '.pdf');
    }
}