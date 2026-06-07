<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityLog; // 🌿 Import model ActivityLog untuk pencatatan audit trail

class VendorPackageController extends Controller
{
    /**
     * TAMPILKAN DAFTAR PAKET (INDEX) - Read Only, No Transaction Needed
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
     * FORM TAMBAH PAKET BARU (CREATE) - Read Only
     */
    public function create()
    {
        $categories = DB::table('categories')->get();
        return view('vendor.package_add', compact('categories'));
    }

    /**
     * PROSES SIMPAN PAKET BARU (STORE)
     */
    public function store(Request $request)
    {
        $request->validate([
            'package_name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'features' => 'nullable|string',
            'main_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $userId = Auth::id();
        $vendorProfile = DB::table('vendor_profiles')->where('user_id', $userId)->first();
        $vendorProfileId = $vendorProfile ? $vendorProfile->id : 0;

        if (!$vendorProfileId) {
            return abort(403, 'Vendor profile not found. Please complete your profile first.');
        }

        $imagePathDb = null;
        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('uploads/packages', 'public');
            $imagePathDb = '/storage/' . $path;
        }

        // 🌿 ACID IMPLEMENTATION: Memulai transaksi database
        DB::beginTransaction();

        try {
            // 1. Insert data paket baru ke database
            $packageId = DB::table('packages')->insertGetId([
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

            // 2. Catat Aktivitas ke Log (Consistency & Durability)
            ActivityLog::create([
                'user_id'    => $userId,
                'activity'   => 'Vendor created a new wedding package.',
                'table_name' => 'packages',
                'record_id'  => $packageId,
                'details'    => json_encode(['package_name' => $request->package_name, 'price' => $request->price]),
                'ip_address' => $request->ip()
            ]);

            // Jika kedua operasi di atas sukses, simpan secara permanen
            DB::commit();

            return redirect()->route('vendor.packages.index')->with('success', 'Package created successfully!');

        } catch (\Exception $e) {
            // Jika transaksi gagal, hapus file gambar yang baru saja di-upload agar storage tidak sampah
            if ($imagePathDb) {
                $uploadedPath = str_replace('/storage/', '', $imagePathDb);
                Storage::disk('public')->delete($uploadedPath);
            }

            // Batalkan semua perubahan di database
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to create package: ' . $e->getMessage());
        }
    }

    /**
     * FORM EDIT PAKET (EDIT) - Read Only
     */
    public function edit($id)
    {
        $package = DB::table('packages')->where('id', $id)->first();

        if (!$package) {
            return abort(404, 'Paket tidak ditemukan.');
        }

        $categories = DB::table('categories')->get();
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

        $userId = Auth::id();
        $packageId = $request->package_id;
        
        // Proteksi awal: Pastikan paket ada sebelum melangkah lebih jauh
        $currentPackage = DB::table('packages')->where('id', $packageId)->first();
        if (!$currentPackage) {
            return redirect()->back()->with('error', 'Package not found.');
        }

        $updateData = [
            'category_id' => $request->category_id,
            'package_name' => $request->package_name,
            'price' => $request->price,
            'description' => $request->description,
            'features' => $request->features,
            'updated_at' => now(),
        ];

        $newImagePath = null;
        $oldImagePathToDelete = null;

        if ($request->hasFile('main_image')) {
            // Simpan gambar baru ke variabel sementara (jangan hapus gambar lama dulu sebelum DB sukses)
            $path = $request->file('main_image')->store('uploads/packages', 'public');
            $newImagePath = '/storage/' . $path;
            $updateData['main_image'] = $newImagePath;

            if ($currentPackage->main_image) {
                $oldImagePathToDelete = str_replace('/storage/', '', $currentPackage->main_image);
            }
        }
        DB::beginTransaction();

        try {
            // 1. Eksekusi update tabel packages
            DB::table('packages')->where('id', $packageId)->update($updateData);

            // 2. Catat Perubahan ke Log Aktivitas
            ActivityLog::create([
                'user_id'    => $userId,
                'activity'   => 'Vendor updated package details.',
                'table_name' => 'packages',
                'record_id'  => $packageId,
                'details'    => json_encode([
                    'old_name'  => $currentPackage->package_name, 
                    'new_name'  => $request->package_name,
                    'image_changed' => $request->hasFile('main_image')
                ]),
                'ip_address' => $request->ip()
            ]);
            DB::commit();

            // Setelah DB sukses dikunci, baru aman menghapus gambar lama fisik
            if ($oldImagePathToDelete) {
                Storage::disk('public')->delete($oldImagePathToDelete);
            }

            return redirect()->route('vendor.packages.index')->with('success', 'Package updated successfully!');

        } catch (\Exception $e) {
            if ($newImagePath) {
                $temporaryPath = str_replace('/storage/', '', $newImagePath);
                Storage::disk('public')->delete($temporaryPath);
            }

            // Batalkan transaksi database kembali ke titik awal
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update package: ' . $e->getMessage());
        }
    }

    /**
     * HAPUS PAKET (DELETE)
     */
    public function delete(Request $request, $id) // Ditambahkan objek Request untuk mendeteksi IP Log
    {
        $userId = Auth::id();
        $package = DB::table('packages')->where('id', $id)->first();

        if (!$package) {
            return redirect()->route('vendor.packages.index')->with('error', 'Package not found.');
        }

        // 🌿 ACID IMPLEMENTATION: Memulai transaksi database
        DB::beginTransaction();

        try {
            // 1. Catat Log Aktivitas Penghapusan sebelum datanya hilang dari tabel utama (Audit Trail)
            ActivityLog::create([
                'user_id'    => $userId,
                'activity'   => 'Vendor deleted a wedding package.',
                'table_name' => 'packages',
                'record_id'  => $id,
                'details'    => json_encode(['deleted_package_name' => $package->package_name]),
                'ip_address' => $request->ip()
            ]);

            // 2. Eksekusi hapus data dari tabel packages
            DB::table('packages')->where('id', $id)->delete();

            // Kunci transaksi database
            DB::commit();

            // 3. Setelah DB aman terhapus, bersihkan file fisik gambar dari local storage Laragon
            if ($package->main_image) {
                $oldPath = str_replace('/storage/', '', $package->main_image);
                Storage::disk('public')->delete($oldPath);
            }

            return redirect()->route('vendor.packages.index')->with('success', 'Package deleted successfully!');

        } catch (\Exception $e) {
            // Batalkan penghapusan di database jika log gagal tersimpan
            DB::rollBack();
            return redirect()->route('vendor.packages.index')->with('error', 'Failed to delete package: ' . $e->getMessage());
        }
    }

    /**
     * SISI PUBLIK: DETAIL PAKET (SHOW) - Read Only
     */
    public function show($id)
    {
        $package = DB::table('packages as p')
            ->leftJoin('vendor_profiles as vp', 'p.vendor_id', '=', 'vp.id')
            ->join('users as u', 'vp.user_id', '=', 'u.id')
            ->leftJoin('categories as c', 'p.category_id', '=', 'c.id')
            ->where('p.id', $id)
            ->select('p.*', 'vp.business_name', 'vp.profile_image', 'u.name as user_name', 'c.name as category_name')
            ->first();

        if (!$package) {
            return abort(404, 'Maaf, paket pernikahan tidak ditemukan.');
        }

        $reviewsQuery = DB::table('reviews as r')
            ->join('bookings as b', 'r.booking_id', '=', 'b.id')
            ->join('customer_profiles as cp', 'r.customer_id', '=', 'cp.id')
            ->where('b.package_id', $id);

        $averageRating = $reviewsQuery->avg('r.rating') ?? 0;
        $totalReviews = $reviewsQuery->count();

        $reviews = $reviewsQuery
            ->select('r.id', 'r.rating', 'r.comment', 'r.vendor_reply', 'r.replied_at', 'r.created_at', 'cp.full_name as customer_name')
            ->orderBy('r.created_at', 'desc')
            ->paginate(5);

        $reviews->getCollection()->transform(function ($review) {
            $name = trim($review->customer_name);
            $length = strlen($name);
            
            if ($length > 2) {
                $first = substr($name, 0, 1);
                $last = substr($name, -1);
                $review->masked_name = $first . '***' . $last;
            } else {
                $review->masked_name = 'A***';
            }
            
            return $review;
        });

        return view('customer.package_detail', compact('package', 'reviews', 'averageRating', 'totalReviews'));
    }

    /**
     * SISI CUSTOMER: HALAMAN CHECKOUT (CHECKOUT) - Read Only
     */
    public function checkout($id)
    {
        if (Auth::user()->role !== 'customer') {
            return abort(403, 'Unauthorized access. Only customers can checkout packages.');
        }

        $package = DB::table('packages as p')
            ->leftJoin('vendor_profiles as vp', 'p.vendor_id', '=', 'vp.id')
            ->join('users as u', 'vp.user_id', '=', 'u.id')
            ->leftJoin('categories as c', 'p.category_id', '=', 'c.id')
            ->where('p.id', $id)
            ->select('p.*', 'vp.business_name as profile_business_name', 'c.name as category_name', 'u.name as user_name')
            ->first();

        if (!$package) {
            return abort(404, 'Paket tidak ditemukan.');
        }

        return view('customer.checkout', compact('package'));
    }
}