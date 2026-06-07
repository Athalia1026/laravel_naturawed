<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorPortfolioController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Ambil data profil bisnis vendor berdasarkan user_id login
        $vendorProfile = DB::table('vendor_profiles')
            ->where('user_id', $userId)
            ->first();

        // 🌿 DEFENSIVE STRATEGY: Gunakan variabel penampung aman untuk mencegah "Attempt to read property id on null"
        $vendorId = $vendorProfile ? $vendorProfile->id : 0;
        
        // Setup objek default awal untuk mencegah error di tampilan Blade jika data kosong
        $ratingStats = (object) ['average_rating' => 0, 'total_reviews' => 0];
        $myPackages = collect();

        // 2. Ambil semua paket yang dimiliki oleh vendor ini (Jika vendorId = 0, kueri akan mengembalikan koleksi kosong secara aman)
        $myPackages = DB::table('packages as p')
            ->leftJoin('categories as c', 'p.category_id', '=', 'c.id')
            ->where('p.vendor_id', $vendorId)
            ->select('p.*', 'c.name as category_name')
            ->orderBy('p.created_at', 'desc')
            ->get();

        // 🌿 PERBAIKAN UTAMA: Bungkus query statistik rating dengan kondisi aman.
        // Query hanya dieksekusi jika profil vendor sudah terbuat (vendorId tidak bernilai 0).
        if ($vendorId !== 0) {
            $ratingStats = DB::table('reviews')
                ->where('vendor_id', $vendorId) // Menggunakan $vendorId yang sudah divalidasi, bukan $vendorProfile->id langsung
                ->select(
                    DB::raw('AVG(rating) as average_rating'),
                    DB::raw('COUNT(id) as total_reviews')
                )
                ->first();
        }

        // 3. Alirkan data menuju file Blade portfolio (Data dipastikan 100% aman untuk memicu @empty di Blade)
        return view('vendor.portfolio', compact('myPackages', 'vendorProfile', 'ratingStats'));
    }
}