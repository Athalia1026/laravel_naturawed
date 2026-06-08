<?php

namespace App\Http\Controllers\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\VendorProfile;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Mengambil maksimal 6 paket aktif (Konversi getActivePackages(6) dari Native)
        $ecoPackages = DB::table('packages as p')
            ->leftJoin('vendor_profiles as vp', 'p.vendor_id', '=', 'vp.id')
            ->leftJoin('categories as c', 'p.category_id', '=', 'c.id')
            ->join('users as u', 'vp.user_id', '=', 'u.id')
            ->where('p.status', 'active')
            ->select('p.*', 'vp.business_name', 'u.name as user_name', 'c.name as category_name')
            ->orderBy('p.created_at', 'desc')
            ->take(6)
            ->get();

       // 2. LOGIKA BARU: Mengambil 3 Vendor Terbaik dari Database
        $recommendedVendorsData = VendorProfile::withAvg('reviews', 'rating')
            ->with(['reviews' => function ($query) {
                // Ambil review terakhir untuk ditampilkan sebagai testimoni
                $query->latest(); 
            }])
            // Urutkan dari rating rata-rata paling tinggi
            ->orderBy('reviews_avg_rating', 'desc')
            ->take(3)
            ->get();

            // Memetakan data asli dari database agar strukturnya cocok dengan tampilan Blade kamu
        $recommendedVendors = $recommendedVendorsData->map(function ($vendor) {
            $latestReview = $vendor->reviews->first();
            
            return [
                "id" => $vendor->id,
                "name" => $vendor->business_name,
                "author" => $latestReview ? "Verified Customer" : "NaturaWed",
                "img" => $vendor->profile_image ? asset($vendor->profile_image) : "https://images.unsplash.com/photo-1520854221256-17451cc331bf?q=80&w=2070&auto=format&fit=crop",
                "review" => $latestReview ? $latestReview->comment : ($vendor->bio ?? "Vendor hebat yang patut dipertimbangkan untuk hari spesialmu."),
                "rating" => $vendor->reviews_avg_rating ? number_format($vendor->reviews_avg_rating, 1) : "5.0"
            ];
        });

        // 3. Data Array Static Wedding Deals (Sesuai berkas asli Anda)
        $weddingDeals = [
            [
                "id" => 1,
                "title" => "Rodeo Bliss",
                "author" => "Ocean Wed n Co.",
                "price" => "Rp 275.000.000",
                "oldPrice" => "Rp 300.000.000",
                "img" => "https://images.unsplash.com/photo-1537633552985-df8429e8048b?q=80&w=2070&auto=format&fit=crop",
                "desc" => "An intimate countryside celebration capturing with your love story in a cozy atmosphere."
            ],
            [
                "id" => 2,
                "title" => "Luxe Signature",
                "author" => "Ocean Wed n Co.",
                "price" => "Rp 320.000.000",
                "oldPrice" => null,
                "img" => "https://images.unsplash.com/photo-1519225421980-715cb0215aed?q=80&w=2070&auto=format&fit=crop",
                "desc" => "An elegant evening with refined details and enjoy your love story in your wedding journey."
            ]
        ];

        // 4. Data Array Static Video (Sesuai berkas asli Anda)
        $videos = [
            ["user" => "@evergreenatelier", "views" => "752", "img" => "https://images.unsplash.com/photo-1583939003579-730e3918a45a?q=80&w=1974&auto=format&fit=crop"],
            ["user" => "@naturevibes", "views" => "752", "img" => "https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=1974&auto=format&fit=crop"],
            ["user" => "@evergreenatelier", "views" => "1.2k", "img" => "https://images.unsplash.com/photo-1511795409834-ef04bbd61622?q=80&w=1974&auto=format&fit=crop"],
            ["user" => "@siennaart", "views" => "456", "img" => "https://images.unsplash.com/photo-1522673607200-1648832cee98?q=80&w=1974&auto=format&fit=crop"],
            ["user" => "@honeywood", "views" => "971", "img" => "https://images.unsplash.com/photo-1469334031218-e382a71b716b?q=80&w=1974&auto=format&fit=crop"]
        ];

        return view('customer.home', compact('ecoPackages', 'recommendedVendors', 'weddingDeals', 'videos'));
    }
}