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
            'instagram' => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'team_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'team_description' => 'nullable|string|max:255',
        ]);

        // Ambil profil lama untuk mengamankan path berkas lama jika tidak diubah
        $oldProfile = DB::table('vendor_profiles')->where('user_id', $userId)->first();
        
        $profileImagePath = $oldProfile ? $oldProfile->profile_image : null;
        $teamImagePath = $oldProfile ? $oldProfile->team_image : null;
        $coverImagePath = $oldProfile ? $oldProfile->cover_image : null;

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = 'logo-' . $userId . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/vendors'), $filename);
            $profileImagePath = 'uploads/vendors/' . $filename;
        }

        // B. Proses Simpan Gambar Foto Bersama Tim / Meet The Team (Jika Ada)
        if ($request->hasFile('team_image')) {
            $file = $request->file('team_image');
            $filename = 'team-' . $userId . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/teams'), $filename);
            $teamImagePath = 'uploads/teams/' . $filename;
        }

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = 'cover-' . $userId . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/covers'), $filename);
            $coverImagePath = 'uploads/covers/' . $filename;
        }

        // 1. Update nama studio di tabel users (kolom name bawaan breeze)
        DB::table('users')
            ->where('id', $userId)
            ->update(['name' => $request->business_name]);

        // 2. Update atau buat data alamat baru di tabel vendor_profiles
        DB::table('vendor_profiles')
            ->updateOrInsert(
                ['user_id' => $userId],
                [
                    'address' => $request->address,
                    'bio' => $request->bio,
                    'instagram' => $request->instagram,
                    'website' => $request->website,
                    'profile_image' => $profileImagePath,
                    'cover_image' => $coverImagePath,
                    'team_image' => $teamImagePath,
                    'team_description' => $request->team_description,
                    'updated_at' => now()
                ]
            );

        return redirect()->route('vendor.dashboard')->with('success', 'Profile updated successfully!');
    }
}