<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;  // Menggantikan BookingModel.php
use App\Models\Package;  // Menggantikan PackageModel.php

class VendorController extends Controller
{
    // Konstruktor opsional jika ingin menyimpan instance model ke property
    protected $bookingModel;
    protected $packageModel;

    public function __construct()
    {
        // Di Laravel, kita menggunakan instansiasi standar Eloquent
        $this->booking = new Booking();
        $this->package = new Package();
    }

    /**
     * HALAMAN DASHBOARD VENDOR
     */
    public function dashboard()
    {
        // Mengambil ID user yang sedang login via Auth Laravel
        $userId = Auth::id();
        
        // Memanggil method kustom dari model Eloquent Anda
        $vendorProfileId = $this->booking->getVendorProfileId($userId);

        if (!$vendorProfileId) {
            return abort(403, 'Vendor profile not found. Please complete your profile first.');
        }

        // Ambil data untuk widget dashboard
        $recentOrders = $this->booking->getRecentOrdersForVendor($vendorProfileId);
        $totalOrders = $this->booking->getTotalOrdersForVendor($vendorProfileId);
        $activePackagesCount = $this->package->getActivePackagesCountByVendor($vendorProfileId);

        // Melempar data ke view Blade dengan compact()
        return view('vendor.dashboard-vendor', compact(
            'recentOrders',
            'totalOrders',
            'activePackagesCount'
        ));
    }

    /**
     * HALAMAN PORTFOLIO VENDOR
     */
    public function portfolio()
    {
        $userId = Auth::id();
        
        // Mengambil data paket milik vendor
        $myPackages = $this->package->getPackagesByVendor($userId);

        // Mengarahkan ke resources/views/vendor/portfolio.blade.php
        return view('vendor.portfolio', compact('myPackages'));
    }
}