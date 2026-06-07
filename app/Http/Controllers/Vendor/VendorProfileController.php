<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog; // 🌿 Import model ActivityLog untuk audit trail

class VendorProfileController extends Controller
{
    /**
     * Menampilkan Form Edit Profile - Read Only, No Transaction Needed
     */
    public function edit()
    {
        $userId = Auth::id();

        $vendorProfile = DB::table('vendor_profiles')
            ->where('user_id', $userId)
            ->first();

        return view('vendor.edit', compact('vendorProfile'));
    }

    /**
     * Memproses Perubahan Data Form dengan Dukungan Transaksi ACID
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

        // Variabel penampung file baru yang berhasil di-upload secara fisik (untuk backup rollback)
        $newFilesUploaded = [];

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = 'logo-' . $userId . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/vendors'), $filename);
            $profileImagePath = 'uploads/vendors/' . $filename;
            $newFilesUploaded[] = public_path($profileImagePath);
        }

        if ($request->hasFile('team_image')) {
            $file = $request->file('team_image');
            $filename = 'team-' . $userId . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/teams'), $filename);
            $teamImagePath = 'uploads/teams/' . $filename;
            $newFilesUploaded[] = public_path($teamImagePath);
        }

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = 'cover-' . $userId . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/covers'), $filename);
            $coverImagePath = 'uploads/covers/' . $filename;
            $newFilesUploaded[] = public_path($coverImagePath);
        }

        // 🌿 ACID IMPLEMENTATION: Memulai transaksi database
        DB::beginTransaction();

        try {
            // 1. Update nama studio di tabel users (kolom name bawaan breeze)
            DB::table('users')
                ->where('id', $userId)
                ->update(['name' => $request->business_name]);

            // 2. Update atau buat data baru di tabel vendor_profiles
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

            // Ambil kembali profile ID untuk keperluan relasi log aktivitas
            $currentProfile = DB::table('vendor_profiles')->where('user_id', $userId)->first();

            // 3. Catat Aktivitas Perubahan Profil ke Log (Consistency & Durability)
            ActivityLog::create([
                'user_id'    => $userId,
                'activity'   => 'Vendor updated business profile details.',
                'table_name' => 'vendor_profiles',
                'record_id'  => $currentProfile->id ?? null,
                'details'    => json_encode([
                    'business_name' => $request->business_name,
                    'has_uploaded_images' => !empty($newFilesUploaded)
                ]),
                'ip_address' => $request->ip()
            ]);
            DB::commit();

            if ($oldProfile) {
                if ($request->hasFile('profile_image') && $oldProfile->profile_image && file_exists(public_path($oldProfile->profile_image))) {
                    @unlink(public_path($oldProfile->profile_image));
                }
                if ($request->hasFile('team_image') && $oldProfile->team_image && file_exists(public_path($oldProfile->team_image))) {
                    @unlink(public_path($oldProfile->team_image));
                }
                if ($request->hasFile('cover_image') && $oldProfile->cover_image && file_exists(public_path($oldProfile->cover_image))) {
                    @unlink(public_path($oldProfile->cover_image));
                }
            }

            return redirect()->route('vendor.dashboard')->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            //Jika database gagal menulis data, hapus file baru yang terlanjur ter-upload ke folder public
            foreach ($newFilesUploaded as $filePath) {
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }

            // Batalkan seluruh mutasi data di database kembali ke titik nol
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }
}