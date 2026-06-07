<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    /**
     * Menampilkan katalog seluruh paket pernikahan aktif
     */
    public function index()
    {
        // 1. Tarik maksimal 100 paket aktif beserta relasi kategori & profil bisnisnya
        $allPackages = DB::table('packages as p')
            ->leftJoin('vendor_profiles as vp', 'p.vendor_id', '=', 'vp.id')
            ->join('users as u', 'vp.user_id', '=', 'u.id')
            ->leftJoin('categories as c', 'p.category_id', '=', 'c.id')
            ->where('p.status', 'active')
            ->select('p.*', 'vp.business_name', 'u.name as user_name', 'c.name as category_name')
            ->orderBy('p.created_at', 'desc')
            ->take(100)
            ->get();

        // 2. Ambil data kategori dinamis untuk menyuplai komponen select filter
        $categories = DB::table('categories')->get();
       //dd($allPackages);
        return view('customer.vendors', compact('allPackages', 'categories'));
    }
}