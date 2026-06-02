<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

        // Validation with new fields
        $request->validate([
            'business_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            // 1. Update nama studio di tabel users (kolom name bawaan breeze)
            DB::table('users')
                ->where('id', $userId)
                ->update(['name' => $request->business_name]);

            // 2. Prepare data for vendor_profiles update
            $updateData = [
                'address' => $request->address,
                'bio' => $request->bio ?? null,
                'updated_at' => now()
            ];

            // 3. Handle profile image upload
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                
                // Get current profile data to delete old image
                $currentProfile = DB::table('vendor_profiles')
                    ->where('user_id', $userId)
                    ->first();

                // Delete old image if exists
                if ($currentProfile && $currentProfile->profile_image) {
                    // Remove /storage/ prefix to get the actual path in storage/app/public/
                    $oldImagePath = str_replace('/storage/', '', $currentProfile->profile_image);
                    if (Storage::disk('public')->exists($oldImagePath)) {
                        Storage::disk('public')->delete($oldImagePath);
                    }
                }

                // Store new image
                $newFileName = 'vendor-' . $userId . '-' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads/vendors', $newFileName, 'public');
                
                // Save the /storage/ path to database
                $updateData['profile_image'] = '/storage/' . $path;
            }

            // 4. Update atau buat data baru di tabel vendor_profiles
            DB::table('vendor_profiles')
                ->updateOrInsert(
                    ['user_id' => $userId],
                    $updateData
                );

            return redirect()->route('vendor.dashboard')->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}