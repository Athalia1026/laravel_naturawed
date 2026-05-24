<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Memproses data pendaftaran pesanan baru dari form checkout
     */
    public function store(Request $request)
    {
        // 1. Validasi Keamanan Input Form
        $request->validate([
            'package_id' => 'required|integer',
            'total_price' => 'required|numeric',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'event_date' => 'required|date',
            'event_location' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $userId = Auth::id();

        // 2. Dapatkan ID Profil Customer dari user yang sedang login
        $customerProfile = DB::table('customer_profiles')->where('user_id', $userId)->first();
        $customerId = $customerProfile ? $customerProfile->id : null;

        if (!$customerId) {
            // Jika profil belum lengkap, buat data profil dasar secara otomatis
            $customerId = DB::table('customer_profiles')->insertGetId([
                'user_id' => $userId,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            // Jika sudah ada, update nomor telepon dan nama lengkap terbaru
            DB::table('customer_profiles')->where('id', $customerId)->update([
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'updated_at' => now()
            ]);
        }

        // 3. Simpan Pesanan ke Tabel Bookings
        $bookingId = DB::table('bookings')->insertGetId([
            'customer_id' => $customerId,
            'package_id' => $request->package_id,
            'event_date' => $request->event_date,
            'event_location' => $request->event_location,
            'notes' => $request->notes,
            'total_price' => $request->total_price,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($bookingId) {
            // 4. Otomatis buat entri tagihan awal di tabel payments dengan status 'unpaid'
            DB::table('payments')->insert([
                'booking_id' => $bookingId,
                'amount' => $request->total_price,
                'status' => 'unpaid',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Alihkan halaman menuju instruksi pembayaran
            return redirect()->route('customer.payment.show', ['booking_id' => $bookingId]);
        }

        return back()->with('error', 'Gagal memproses pemesanan Anda.');
    }

    public function checkout($id)
    {
        // Mengambil data spesifik paket beserta nama bisnis vendornya
        $package = DB::table('packages as p')
            ->leftJoin('vendor_profiles as vp', 'p.vendor_id', '=', 'vp.id')
            ->where('p.id', $id)
            ->select('p.*', 'vp.business_name')
            ->first();

        if (!$package) {
            return abort(404, 'Paket pernikahan tidak ditemukan.');
        }

        // Mengalirkan data menuju berkas views/customer/checkout.blade.php
        return view('customer.checkout', compact('package'));
    }

    public function history(Request $request)
    {
        $activeTab = $request->query('tab', 'All');
        $userId = Auth::id();

        // 1. Inisialisasi Base Query Builder dengan relasi JOIN tabel packages & payments
        $query = DB::table('bookings as b')
            ->join('packages as p', 'b.package_id', '=', 'p.id')
            ->leftJoin('payments as pay', 'b.id', '=', 'pay.booking_id')
            ->where('b.customer_id', function ($subquery) use ($userId) {
                $subquery->select('id')
                    ->from('customer_profiles')
                    ->where('user_id', $userId)
                    ->limit(1);
            })
            ->select(
                'b.*',
                'p.package_name',
                'p.main_image',
                'p.price as package_price',
                'pay.status as payment_status',
                'pay.amount as total_paid'
            );

        // 2. Logika Filtrasi Klausa WHERE Berdasarkan Tab Aktif
        if ($activeTab === 'Ongoing') {
            $query->where(function ($q) {
                $q->whereIn('pay.status', ['pending_verification', 'unpaid'])
                    ->orWhereNull('pay.status');
            });
        } elseif ($activeTab === 'Completed') {
            $query->where('pay.status', '=', 'success');
        } elseif ($activeTab === 'Canceled') {
            $query->where('b.status', '=', 'canceled');
        }

        // 3. Eksekusi Pengambilan Data Urut Berdasarkan Tanggal Dibuat Terbaru
        $historyItems = $query->orderBy('b.created_at', 'desc')->get();

        return view('customer.history', compact('historyItems', 'activeTab'));
    }
}