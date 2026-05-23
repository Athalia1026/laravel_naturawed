<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorProfileController extends Controller
{
    /**
     * Menampilkan Form Edit Profile
     */
    public function edit()
    {
        $userId = Auth::id();

        // Mengambil data spesifik dari tabel vendor_profiles untuk alamat bisnis
        $vendorProfile = DB::table('vendor_profiles')
            ->where('user_id', $userId)
            ->first();

        return view('vendor.edit', compact('vendorProfile'));
    }

    /**
     * Memproses Perubahan Data Form
     */
    public function update(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'business_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        // 1. Update nama studio di tabel users (kolom name bawaan breeze)
        DB::table('users')
            ->where('id', $userId)
            ->update(['name' => $request->business_name]);

        // 2. Update atau buat data alamat baru di tabel vendor_profiles
        DB::table('vendor_profiles')
            ->updateOrInsert(
                ['user_id' => $userId],
                ['address' => $request->address, 'updated_at' => now()]
            );

        return redirect()->route('vendor.dashboard')->with('success', 'Profile updated successfully!');
    }
}