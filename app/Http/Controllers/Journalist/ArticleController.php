<?php

namespace App\Http\Controllers\Journalist;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;      // WAJIB DITAMBAHKAN UNTUK ACID
use Illuminate\Support\Facades\Storage; // WAJIB DITAMBAHKAN UNTUK HAPUS GAMBAR

class ArticleController extends Controller
{
    // FUNGSI BARU: Untuk Halaman Publik Inspirasi
    public function index() {
        // Ambil 1 artikel paling baru untuk bagian Hero / Featured
        $featuredArticle = Article::latest()->first();

        // Ambil artikel sisanya (selain yang featured) untuk bagian grid
        $otherArticles = collect();
        if ($featuredArticle) {
            $otherArticles = Article::where('id', '!=', $featuredArticle->id)
                                    ->latest()
                                    ->get();
        }

        return view('articles.inspiration', compact('featuredArticle', 'otherArticles'));
    }

    // Untuk Jurnalis
    public function dashboard() {
        $myArticles = Article::where('journalist_id', Auth::id())->get();
        return view('journalist.dashboard', compact('myArticles'));
    }

    public function create() {
        return view('journalist.write_article');
    }

    // FUNGSI STORE YANG SUDAH DI-UPGRADE DENGAN ACID
    public function store(Request $request) {
        // 1. Validasi input dari form
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'content' => 'required|string',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Proses Upload Gambar (Dilakukan di luar transaksi DB)
        $imagePath = null;
        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('articles', 'public');
        }

        // =======================================================================
        // 3. IMPLEMENTASI ACID (ATOMICITY) MENGGUNAKAN DATABASE TRANSACTION
        // =======================================================================
        DB::beginTransaction(); // Kunci database, mulai proses transaksi

        try {
            // Proses Insert ke tabel articles
            Article::create([
                'journalist_id' => Auth::id(), // Menyimpan jejak siapa yang upload
                'title' => $request->input('title'),
                'author_name' => $request->input('author'),
                'category' => $request->input('category'),
                'content' => $request->input('content'), 
                'image_url' => $imagePath ? '/storage/' . $imagePath : null, 
            ]);

            // Jika eksekusi berhasil sampai sini, permanenkan data ke database! (DURABILITY)
            DB::commit();

            return redirect()->route('journalist.dashboard')
                             ->with('success', 'Artikel inspirasi berhasil dipublikasikan!');

        } catch (\Exception $e) {
            // Jika terjadi error di database, BATALKAN semua penyimpanan! (ATOMICITY & CONSISTENCY)
            DB::rollBack();

            // Hapus gambar yang sudah terlanjur ter-upload ke folder agar tidak menjadi sampah
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            // Kembalikan ke halaman form dengan pesan error
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    // Untuk Detail Artikel (Shared oleh Jurnalis & Customer)
    public function show($id) {
        $article = Article::findOrFail($id);
        return view('articles.article_detail', compact('article'));
    }
}