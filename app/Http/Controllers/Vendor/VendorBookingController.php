<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;

class VendorBookingController extends Controller
{

    /**
     * TAMPILKAN SEMUA DATA PESANAN MASUK (VIEW ALL)
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $userId = Auth::id();
        $activeTab = $request->query('tab', 'All'); // Menangkap status tab filter dari URL

        // A. Dapatkan ID Profil Vendor yang sedang aktif login
        $vendorProfile = DB::table('vendor_profiles')
            ->where('user_id', $userId)
            ->first();

        if (!$vendorProfile) {
            return redirect()->route('vendor.dashboard')->with('error', 'Vendor profile not found.');
        }

        // B. Rancang Blueprint Kueri Fluent Gabungan (Packages -> Bookings -> Customer)
        $query = DB::table('bookings as b')
            ->join('packages as p', 'b.package_id', '=', 'p.id')
            ->join('customer_profiles as cp', 'b.customer_id', '=', 'cp.id')
            ->where('p.vendor_id', $vendorProfile->id)
            ->select(
                'b.id as booking_id',
                'b.booking_status',
                'b.payment_status',
                'b.total_price',
                'b.estimated_guests',
                'b.event_date',
                'p.package_name',
                'cp.full_name as customer_name'
            )
            ->orderBy('b.created_at', 'desc');

        // 🌿 ACID & RETRIEVAL STRATEGY: Filter data secara dinamis berdasarkan tab aktif
        if ($activeTab === 'Pending') {
            $query->where('b.booking_status', 'pending_review');
        } elseif ($activeTab === 'Approved') {
            $query->where('b.booking_status', 'approved');
        } elseif ($activeTab === 'Rejected') {
            $query->where('b.booking_status', 'rejected');
        }

        // C. Ambil data menggunakan Pagination (Wajib untuk standardisasi Tugas Akhir/Informasi Sistem)
        $orders = $query->paginate(10)->withQueryString();

        return view('vendor.bookings', compact('orders', 'activeTab'));
    }
    /**
     * Approve a booking request
     */
    public function approve(Request $request, $bookingId) // Ditambahkan objek Request untuk menangkap IP Address
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

        // 🌿 ACID IMPLEMENTATION: Memulai transaksi database
        DB::beginTransaction();

        try {
            // C. Update booking status to approved
            DB::table('bookings')
                ->where('id', $bookingId)
                ->update([
                    'booking_status' => 'approved',
                    'updated_at' => now()
                ]);

            // D. Catat Aktivitas ke Log (Consistency & Durability)
            ActivityLog::create([
                'user_id'    => $userId,
                'activity'   => 'Vendor approved booking request.',
                'table_name' => 'bookings',
                'record_id'  => $bookingId,
                'details'    => json_encode(['previous_status' => $booking->booking_status, 'new_status' => 'approved']),
                'ip_address' => $request->ip()
            ]);

            // Jika kedua operasi di atas berhasil tanpa interupsi, kunci data permanen
            DB::commit();

            return back()->with('success', 'Booking approved successfully! Customer can now proceed with payment.');

        } catch (\Exception $e) {
            // Jika terjadi kegagalan (misal: tabel log eror), batalkan semua perubahan status booking
            DB::rollBack();
            return back()->with('error', 'Failed to approve booking: ' . $e->getMessage());
        }
    }

    /**
     * Reject a booking request
     */
    public function reject(Request $request, $bookingId) 
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

    DB::beginTransaction();

    try {
        // C. Update booking status to 'rejected'
        DB::table('bookings')
            ->where('id', $bookingId)
            ->update([
                'booking_status' => 'rejected',
                'payment_status' => 'canceled', // Langsung set payment status ke 'canceled' untuk sinkronisasi
                'updated_at' => now()
            ]);

        // 🌟 D. SINKRONISASI PEMBATALAN INVOICE: Update status payment terkait menjadi 'canceled'
        DB::table('payments')
            ->where('booking_id', $bookingId)
            ->update([
                'status' => 'canceled',
                'updated_at' => now()
            ]);

        // E. Catat Aktivitas ke Log (Consistency & Durability)
        ActivityLog::create([
            'user_id'    => $userId,
            'activity'   => 'Vendor rejected booking request and canceled the invoice.',
            'table_name' => 'bookings',
            'record_id'  => $bookingId,
            'details'    => json_encode([
                'previous_booking_status' => $booking->booking_status, 
                'new_booking_status' => 'rejected',
                'payment_status_set' => 'canceled'
            ]),
            'ip_address' => $request->ip()
        ]);

        // Kunci semua perubahan data secara permanen jika tidak ada exception
        DB::commit();

        return back()->with('success', 'Booking rejected and associated invoice canceled successfully.');

    } catch (\Exception $e) {
        // 🌿 RECOVERY: Batalkan mutasi di tabel bookings & payments jika salah satu proses gagal
        DB::rollBack();
        return back()->with('error', 'Failed to reject booking: ' . $e->getMessage());
    }
}
}