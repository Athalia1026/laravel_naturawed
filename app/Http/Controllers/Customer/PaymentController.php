<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityLog;

class PaymentController extends Controller
{
    /**
     * Menampilkan lembar panduan instruksi transfer bank
     */
    public function showPayment(Request $request)
    {
        $booking_id = $request->query('booking_id');

        if (!$booking_id) {
            return redirect()->route('home');
        }

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
     * Dengan Implementasi Transaksi ACID dan Audit Trail Terproteksi
     */
    public function store(Request $request)
    {
        // 1. Validasi Keamanan Berkas Sisi Server
        $request->validate([
            'booking_id' => 'required|integer',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        
        $imagePathDb = null;

        try {
            $bookingId = $request->booking_id;

            // 2. Eksekusi Penyimpanan Gambar ke Folder Storage Public
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $newFileName = 'pay-' . $bookingId . '-' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads/payments', $newFileName, 'public');
                $imagePathDb = '/storage/' . $path; 
            }

            if (!$imagePathDb) {
                DB::rollBack(); // Pastikan di-rollback sebelum return exit
                return response()->json(['status' => 'error', 'message' => 'Gagal memproses unggah dokumen.'], 400);
            }

            // 3. Update data tabel payments menggunakan pendekatan fleksibel updateOrInsert
            // Mengatasi kendala jika row data payments belum ter-generate sempurna sebelumnya
            $paymentUpdated = DB::table('payments')
                ->where('booking_id', $bookingId)
                ->update([
                    'payment_proof' => $imagePathDb,
                    'status'        => 'paid', // Menggunakan 'paid' demi sinkronisasi grafik analitik vendor
                    'updated_at'    => now()
                ]);

            // Jika row belum ada, kita bantu buatkan baru demi menjaga kestabilan alur transaksi
            if (!$paymentUpdated) {
                DB::table('payments')->insert([
                    'booking_id'    => $bookingId,
                    'payment_proof' => $imagePathDb,
                    'status'        => 'paid',
                    'amount'        => $request->amount ?? 0, // Ambil jika dilempar dari request form
                    'created_at'    => now(),
                    'updated_at'    => now()
                ]);
            }

            // 4. Sinkronisasi status di tabel bookings
            DB::table('bookings')
                ->where('id', $bookingId)
                ->update([
                    'payment_status' => 'success', // 'success' agar orderan berpindah ke tab Completed customer
                    'updated_at'     => now()
                ]);

            // 🌟 5. PENERAPAN BARU: Catat Jejak Audit ke Log Aktivitas (Durability)
            ActivityLog::create([
                'user_id'    => Auth::id(),
                'activity'   => 'Customer uploaded payment proof.',
                'table_name' => 'payments',
                'record_id'  => $bookingId,
                'details'    => json_encode(['booking_id' => $bookingId, 'status_set' => 'paid']),
                'ip_address' => $request->ip()
            ]);

            // Kunci perubahan permanen ke database jika semua step di atas sukses tanpa interupsi
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Payment proof submitted successfully.'
            ]);

        } catch (\Exception $e) {
            // 🌿 RECOVERY FILE CLEANUP: Jika query database crash, hapus gambar bukti transfer yang baru di-upload agar storage bersih
            if ($imagePathDb) {
                $uploadedPath = str_replace('/storage/', '', $imagePathDb);
                Storage::disk('public')->delete($uploadedPath);
            }

            // Batalkan semua mutasi data ke titik awal sebelum eror terjadi
            DB::rollBack();
            
            return response()->json(['status' => 'error', 'message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }
}