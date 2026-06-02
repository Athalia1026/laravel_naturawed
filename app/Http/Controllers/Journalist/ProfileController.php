<?php

namespace App\Http\Controllers\Journalist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Menampilkan Form Edit Profile Jurnalis
     */
    public function edit()
    {
        $userId = Auth::id();

        // Mengambil data spesifik dari tabel journalist_profiles
        $profile = DB::table('journalist_profiles')
            ->where('user_id', $userId)
            ->first();

        // Jika profile belum ada di database, buat object kosong sementara agar tidak error di Blade
        if (!$profile) {
            $profile = (object)[
                'full_name' => Auth::user()->name,
                'bio' => null,
                'profile_image' => null,
                'header_image' => null,
            ];
        }

        return view('journalist.profile', compact('profile'));
    }

    /**
     * Memproses Perubahan Data Form
     */
    public function update(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'full_name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'header_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Ambil profil lama untuk mengamankan path berkas lama jika tidak diubah
        $oldProfile = DB::table('journalist_profiles')->where('user_id', $userId)->first();
        
        $profileImagePath = $oldProfile ? $oldProfile->profile_image : null;
        $headerImagePath = $oldProfile ? $oldProfile->header_image : null;

        // Proses Simpan Gambar Profil
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = 'avatar-' . $userId . '-' . time() . '.' . $file->getClientOriginalExtension();
            // Pindahkan langsung ke folder public
            $file->move(public_path('uploads/journalists'), $filename);
            $profileImagePath = 'uploads/journalists/' . $filename;
        }

        // Proses Simpan Gambar Header
        if ($request->hasFile('header_image')) {
            $file = $request->file('header_image');
            $filename = 'header-' . $userId . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/journalists'), $filename);
            $headerImagePath = 'uploads/journalists/' . $filename;
        }

        // 1. Update nama/pen name di tabel users (kolom name bawaan breeze)
        DB::table('users')
            ->where('id', $userId)
            ->update(['name' => $request->full_name]);

        // 2. Update atau buat data profil baru di tabel journalist_profiles
        DB::table('journalist_profiles')
            ->updateOrInsert(
                ['user_id' => $userId],
                [
                    'full_name' => $request->full_name,
                    'bio' => $request->bio,
                    'profile_image' => $profileImagePath,
                    'header_image' => $headerImagePath,
                    'updated_at' => now()
                ]
            );

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}