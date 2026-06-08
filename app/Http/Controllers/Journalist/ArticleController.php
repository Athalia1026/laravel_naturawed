<?php

namespace App\Http\Controllers\Journalist;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{

    public function inspiration()
    {
        // Ambil 1 artikel terbaru sebagai Highlight/Featured
        $featuredArticle = Article::latest()->first();

        // Ambil artikel lainnya (selain yang featured)
        $otherArticles = [];
        if ($featuredArticle) {
            $otherArticles = Article::where('id', '!=', $featuredArticle->id)
                                    ->latest()
                                    ->take(9) // Batasi misalnya 9 artikel
                                    ->get();
        }

        return view('customer.inspiration', compact('featuredArticle', 'otherArticles'));
    }
    // Untuk Jurnalis
    public function dashboard() {
        $myArticles = Article::where('journalist_id', Auth::id())->get();
        return view('journalist.dashboard', compact('myArticles'));
    }

    public function create() {
        return view('journalist.write_article');
    }

    // Untuk Detail Artikel (Shared oleh Jurnalis & Customer)
    public function show($id) {
        $article = Article::findOrFail($id);
        return view('articles.article_detail', compact('article'));
    }

    public function store(Request $request) 
    {
        // 1. Validasi input formulir
        $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:100',
            'category'    => 'required|string|max:50',
            'content'     => 'required|string',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Maksimal 2MB
        ]);

        // 2. Proses unggah gambar
        $imageUrl = null;
        if ($request->hasFile('cover_image')) {
            // Simpan gambar ke folder storage/app/public/articles
            $imagePath = $request->file('cover_image')->store('articles', 'public');
            // Format URL agar bisa diakses dari public
            $imageUrl = '/storage/' . $imagePath;
        }

        // 3. Simpan data ke database
        Article::create([
            'journalist_id' => Auth::id(),
            'title'         => $request->title,
            'author_name'   => $request->author,
            'category'      => $request->category,
            'image_url'     => $imageUrl,
            'content'       => $request->content,
        ]);

        // 4. Kembali ke dashboard dengan pesan sukses
        return redirect()->route('journalist.dashboard')->with('success', 'Article published successfully!');
    }
    
}