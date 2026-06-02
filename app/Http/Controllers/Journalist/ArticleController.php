<?php

namespace App\Http\Controllers\Journalist;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

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

    // FUNGSI BARU: Untuk menyimpan artikel ke database
    public function store(Request $request) {
        // 1. Validasi input dari form
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'content' => 'required|string',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Proses Upload Gambar
        $imagePath = null;
        if ($request->hasFile('cover_image')) {
            // Simpan gambar ke direktori storage/app/public/articles
            $imagePath = $request->file('cover_image')->store('articles', 'public');
        }

        // 3. Simpan data artikel ke database
       Article::create([
            'journalist_id' => Auth::id(),
            'title' => $request->input('title'),
            'author_name' => $request->input('author'),
            'category' => $request->input('category'),
            'content' => $request->input('content'), // <-- Gunakan input() agar aman
            // Format URL agar bisa langsung dibaca oleh tag <img> di Blade
            'image_url' => $imagePath ? '/storage/' . $imagePath : null, 
        ]);

        // 4. Kembali ke halaman dashboard
        return redirect()->route('journalist.dashboard')
                         ->with('success', 'Artikel inspirasi berhasil dipublikasikan!');
    }

    // Untuk Detail Artikel (Shared oleh Jurnalis & Customer)
    public function show($id) {
        $article = Article::findOrFail($id);
        return view('articles.article_detail', compact('article'));
    }
}