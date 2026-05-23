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

        $vendorId = $vendorProfile ? $vendorProfile->id : 0;

        // 2. Ambil semua paket yang dimiliki oleh vendor ini (Konversi fungsi getPackagesByVendor)
        $myPackages = DB::table('packages as p')
            ->leftJoin('categories as c', 'p.category_id', '=', 'c.id')
            ->where('p.vendor_id', $vendorId)
            ->select('p.*', 'c.name as category_name')
            ->orderBy('p.created_at', 'desc')
            ->get();

        // 3. Alirkan data menuju file Blade portfolio
        return view('vendor.portofolio', compact('myPackages', 'vendorProfile'));
    }
}