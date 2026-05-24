<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Menampilkan lembar panduan instruksi transfer bank
     */
    public function showPayment(Request $request)
    {
        $booking_id = $request->query('booking_id');

        if (!$booking_id) {
            return redirect()->route('home'); // Ganti 'history' ke rute history Anda jika sudah ada
        }

        // Kueri Fluent untuk menarik data pembayaran, booking, dan nama paket terkait
        $payment = DB::table('payments as p')
            ->join('bookings as b', 'p.booking_id', '=', 'b.id')
            ->join('packages as pkg', 'b.package_id', '=', 'pkg.id')
            ->where('p.booking_id', $booking_id)
            ->select('p.*', 'pkg.package_name', 'pkg.price as amount')
            ->first();

        if (!$payment) {
            return abort(404, 'Data tagihan pembayaran tidak ditemukan.');
        }

        return view('customer.payment', compact('payment'));
    }

    /**
     * Memproses unggah berkas bukti pembayaran via AJAX (JSON Response)
     */
    public function store(Request $request)
    {
        // 1. Validasi Keamanan Berkas Sisi Server
        $request->validate([
            'booking_id' => 'required|integer',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Batas maksimal 2MB
        ]);

        try {
            $bookingId = $request->booking_id;
            $imagePathDb = null;

            // 2. Eksekusi Penyimpanan Gambar ke Folder Public Laragon
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                
                // Menyusun penamaan berkas kustom sesuai format asli Anda
                $newFileName = 'pay-' . $bookingId . '-' . time() . '.' . $file->getClientOriginalExtension();
                
                // Pindahkan langsung ke direktori public/uploads/payments/
                $file->move(public_path('uploads/payments'), $newFileName);
                $imagePathDb = '/uploads/payments/' . $newFileName;
            }

            if (!$imagePathDb) {
                return response()->json(['status' => 'error', 'message' => 'Gagal memproses unggah dokumen.'], 400);
            }

            // 3. Update Status Data ke Tabel Payments
            $updated = DB::table('payments')
                ->where('booking_id', $bookingId)
                ->update([
                    'payment_proof' => $imagePathDb,
                    'status' => 'pending_verification',
                    'updated_at' => now()
                ]);

            if ($updated) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment proof submitted successfully.'
                ]);
            }

            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui status basis data.'], 500);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }
}
