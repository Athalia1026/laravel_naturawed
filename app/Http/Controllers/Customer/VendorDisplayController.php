<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class VendorDisplayController extends Controller
{
    public function show($id)
    {
        // 1. Ambil data profil dasar dan branding studio vendor
        $vendorProfile = DB::table('vendor_profiles')->where('id', $id)->first();

        if (!$vendorProfile) {
            abort(404, 'Vendor profile not found.');
        }

        // 2. Ambil data nama dan email dari tabel users bawaan Breeze
        $vendorUser = DB::table('users')->where('id', $vendorProfile->user_id)->first();

        // 3. Ambil seluruh paket pernikahan yang aktif ditawarkan oleh vendor ini
        $myPackages = DB::table('packages as p')
            ->leftJoin('categories as c', 'p.category_id', '=', 'c.id')
            ->where('p.vendor_id', $id)
            ->select('p.*', 'c.name as category_name')
            ->orderBy('p.created_at', 'desc')
            ->get();

        $ratingStats = DB::table('reviews')
        ->where('vendor_id', $id)
        ->select(
            DB::raw('AVG(rating) as average_rating'),
            DB::raw('COUNT(id) as total_reviews')
        )
        ->first();
        return view('customer/vendor_detail', compact('vendorProfile', 'vendorUser', 'myPackages', 'ratingStats'));
    }
}