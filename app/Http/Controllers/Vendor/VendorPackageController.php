<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VendorPackageController extends Controller
{
    /**
     * TAMPILKAN DAFTAR PAKET (INDEX)
     */
    public function index()
    {
        $userId = Auth::id();
        $vendorProfile = DB::table('vendor_profiles')->where('user_id', $userId)->first();
        $vendorProfileId = $vendorProfile ? $vendorProfile->id : 0;

        $myPackages = DB::table('packages as p')
            ->leftJoin('categories as c', 'p.category_id', '=', 'c.id')
            ->where('p.vendor_id', $vendorProfileId)
            ->select('p.*', 'c.name as category_name')
            ->orderBy('p.created_at', 'desc')
            ->get();

        return view('vendor.packages', compact('myPackages'));
    }

    /**
     * FORM TAMBAH PAKET BARU (CREATE)
     */
    public function create()
    {
        // Mengambil data kategori untuk disuplai ke dalam element <select> form
        $categories = DB::table('categories')->get();
        return view('vendor.package_add', compact('categories'));
    }

    /**
     * PROSES SIMPAN PAKET BARU (STORE)
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Form (Menggantikan filter ekstensi manual)
        $request->validate([
            'package_name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'features' => 'nullable|string',
            'main_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Maks 2MB
        ]);

        $userId = Auth::id();
        $vendorProfile = DB::table('vendor_profiles')->where('user_id', $userId)->first();
        $vendorProfileId = $vendorProfile ? $vendorProfile->id : 0;

        if (!$vendorProfileId) {
            return abort(403, 'Vendor profile not found. Please complete your profile first.');
        }

        // 2. Proses Upload Gambar Menggunakan Laravel Storage API
        $imagePathDb = null;
        if ($request->hasFile('main_image')) {
            // Otomatis tersimpan di folder: storage/app/public/uploads/packages
            $path = $request->file('main_image')->store('uploads/packages', 'public');
            $imagePathDb = '/storage/' . $path; // Path url yang siap ditembak ke <img src="">
        }

        // 3. Insert ke Database Laragon
        DB::table('packages')->insert([
            'vendor_id' => $vendorProfileId,
            'category_id' => $request->category_id,
            'package_name' => $request->package_name,
            'price' => $request->price,
            'description' => $request->description,
            'features' => $request->features,
            'main_image' => $imagePathDb,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('vendor.packages.index')->with('success', 'Package created successfully!');
    }

    /**
     * FORM EDIT PAKET (EDIT)
     */
    public function edit($id)
    {
        $package = DB::table('packages')->where('id', $id)->first();

        if (!$package) {
            return abort(404, 'Paket tidak ditemukan.');
        }

        $categories = DB::table('categories')->get();
        
        // Memakai file view form yang sama sesuai arsitektur native Anda
        return view('vendor.package_add', compact('package', 'categories'));
    }

    /**
     * PROSES UPDATE DATA PAKET (UPDATE)
     */
    public function update(Request $request)
    {
        $request->validate([
            'package_id' => 'required|integer',
            'package_name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'features' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $packageId = $request->package_id;
        $currentPackage = DB::table('packages')->where('id', $packageId)->first();

        $updateData = [
            'category_id' => $request->category_id,
            'package_name' => $request->package_name,
            'price' => $request->price,
            'description' => $request->description,
            'features' => $request->features,
            'updated_at' => now(),
        ];

        // Jika vendor mengunggah file gambar baru, hapus gambar lama dan simpan yang baru
        if ($request->hasFile('main_image')) {
            if ($currentPackage && $currentPackage->main_image) {
                // Konversi path url ke path storage internal untuk dihapus
                $oldPath = str_replace('/storage/', '', $currentPackage->main_image);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('main_image')->store('uploads/packages', 'public');
            $updateData['main_image'] = '/storage/' . $path;
        }

        DB::table('packages')->where('id', $packageId)->update($updateData);

        return redirect()->route('vendor.packages.index')->with('success', 'Package updated successfully!');
    }

    /**
     * HAPUS PAKET (DELETE)
     */
    public function delete($id)
    {
        $package = DB::table('packages')->where('id', $id)->first();

        if ($package) {
            // Hapus fisik gambar dari disk storage agar tidak memenuhi memori Laragon
            if ($package->main_image) {
                $oldPath = str_replace('/storage/', '', $package->main_image);
                Storage::disk('public')->delete($oldPath);
            }

            DB::table('packages')->where('id', $id)->delete();
        }

        return redirect()->route('vendor.packages.index')->with('success', 'Package deleted successfully!');
    }

    /**
     * SISI PUBLIK: DETAIL PAKET (SHOW)
     */
    public function show($id)
    {
        $package = DB::table('packages as p')
            ->leftJoin('vendor_profiles as vp', 'p.vendor_id', '=', 'vp.id')
            ->leftJoin('categories as c', 'p.category_id', '=', 'c.id')
            ->where('p.id', $id)
            ->select('p.*', 'vp.business_name', 'c.name as category_name')
            ->first();

        if (!$package) {
            return abort(404, 'Maaf, paket pernikahan tidak ditemukan.');
        }

        return view('public.package_detail', compact('package'));
    }

    /**
     * SISI CUSTOMER: HALAMAN CHECKOUT (CHECKOUT)
     */
    public function checkout($id)
    {
        // Proteksi: Hanya Customer yang bisa checkout (Bisa ditangani middleware kustom nanti)
        if (Auth::user()->role !== 'customer') {
            return abort(403, 'Unauthorized access. Only customers can checkout packages.');
        }

        $package = DB::table('packages as p')
            ->leftJoin('vendor_profiles as vp', 'p.vendor_id', '=', 'vp.id')
            ->leftJoin('categories as c', 'p.category_id', '=', 'c.id')
            ->where('p.id', $id)
            ->select('p.*', 'vp.business_name', 'c.name as category_name')
            ->first();

        if (!$package) {
            return abort(404, 'Paket tidak ditemukan.');
        }

        return view('customer.checkout', compact('package'));
    }
}