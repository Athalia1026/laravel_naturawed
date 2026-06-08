<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog; // 🌿 Import model ActivityLog untuk pencatatan audit trail

class BookingController extends Controller
{
    /**
     * Memproses data pendaftaran pesanan baru dari form checkout (ACID-Compliant)
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
            'estimated_guests' => 'required|integer|min:10',
            'notes' => 'nullable|string',
        ]);

        $userId = Auth::id();
        DB::beginTransaction();

        try {
            // 2. Dapatkan atau Buat ID Profil Customer dari user yang sedang login
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
                'estimated_guests' => $request->estimated_guests,
                'notes' => $request->notes,
                'total_price' => $request->total_price,
                'booking_status' => 'pending_review',
                'payment_status' => 'unpaid',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 4. Otomatis buat entri tagihan awal di tabel payments dengan status 'unpaid'
            DB::table('payments')->insert([
                'booking_id' => $bookingId,
                'amount' => $request->total_price,
                'status' => 'unpaid',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            ActivityLog::create([
                'user_id' => $userId,
                'activity' => 'Customer submitted a new booking request.',
                'table_name' => 'bookings',
                'record_id' => $bookingId,
                'details' => json_encode([
                    'package_id' => $request->package_id,
                    'event_date' => $request->event_date,
                    'total_price' => $request->total_price
                ]),
                'ip_address' => $request->ip()
            ]);

            // Jika kelima langkah di atas sukses berjalan tanpa interupsi, kunci data secara permanen
            DB::commit();

            // Redirect ke halaman history dengan pesan sukses
            return redirect()->route('customer.bookings.history')->with('success', 'Booking request submitted! Waiting for vendor approval.');

        } catch (\Exception $e) {
            // 🌿 RECOVERY TRANSACTION: Jika salah satu langkah meledak/gagal, batalkan seluruh rangkaian di atas kembali ke nol
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memproses pemesanan Anda: ' . $e->getMessage());
        }
    }

    /**
     * SISI CUSTOMER: HALAMAN CHECKOUT (CHECKOUT) - Read Only, No Transaction Needed
     */
    public function checkout($id)
    {
        $package = DB::table('packages as p')
            ->leftJoin('vendor_profiles as vp', 'p.vendor_id', '=', 'vp.id')
            ->where('p.id', $id)
            ->select('p.*', 'vp.business_name')
            ->first();

        if (!$package) {
            return abort(404, 'Paket pernikahan tidak ditemukan.');
        }

        return view('customer.checkout', compact('package'));
    }

    /**
     * SISI CUSTOMER: RIWAYAT PEMESANAN (HISTORY) - Read Only, No Transaction Needed
     */
    public function history(Request $request)
    {
        $userId = Auth::id();
        $activeTab = $request->query('tab', 'All');

        $customerProfile = DB::table('customer_profiles')->where('user_id', $userId)->first();
        if (!$customerProfile) {
            return redirect()->route('dashboard')->with('error', 'Please complete your profile first.');
        }

        $query = DB::table('bookings as b')
            ->join('packages as p', 'b.package_id', '=', 'p.id')
            ->where('b.customer_id', $customerProfile->id)
            ->select('b.*', 'p.package_name', 'p.main_image', 'p.price as package_price')
            ->orderBy('b.created_at', 'desc');

        if ($activeTab === 'Ongoing') {
            $query->where(function ($q) {
                $q->where('b.booking_status', 'pending_review')
                    ->orWhere(function ($q2) {
                        $q2->where('b.booking_status', 'approved')
                            ->where('b.payment_status', '!=', 'success');
                    });
            });
        } elseif ($activeTab === 'Completed') {
            $query->where('b.booking_status', 'approved')
                ->where('b.payment_status', 'success');
        } elseif ($activeTab === 'Canceled') {
            $query->where('b.booking_status', 'rejected');
        }

        $historyItems = $query->get();

        return view('customer.history', compact('historyItems', 'activeTab'));
    }

    public function show($id)
    {
        $userId = Auth::id();

        $customerProfile = DB::table('customer_profiles')->where('user_id', $userId)->first();
        if (!$customerProfile) {
            return abort(403, 'Unauthorized access.');
        }

        $booking = DB::table('bookings as b')
            ->join('packages as p', 'b.package_id', '=', 'p.id')
            ->join('vendor_profiles as vp', 'p.vendor_id', '=', 'vp.id')
            ->join('users as u', 'vp.user_id', '=', 'u.id')
            ->leftJoin('payments as pay', 'b.id', '=', 'pay.booking_id')
            ->where('b.id', $id)
            ->select(
                'b.*',
                'p.package_name',
                'p.main_image',
                'vp.business_name as vp_name',
                'u.name as user_name',
                'pay.payment_proof',
                'pay.status as payment_db_status'
            )
            ->first();

        if (!$booking) {
            return abort(404, 'Booking not found.');
        }
        
        $booking->business_name = $booking->vp_name ?: $booking->user_name;

        if ($booking->customer_id !== $customerProfile->id) {
            return abort(403, 'Unauthorized access.');
        }

        $booking->payment_status = $booking->payment_status ?? 'unpaid';
        $booking->booking_status = $booking->booking_status ?? 'pending_review';

        $hasReview = DB::table('reviews')->where('booking_id', $id)->exists();

        return view('customer.booking_detail', compact('booking', 'hasReview'));
    }
}