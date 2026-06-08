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

    public function index() 
    {
        // Redirect ke halaman inspiration sebagai default jika ada yang mengakses /articles
        return redirect()->route('inspiration');
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
        $article = Article::with(['journalist.journalistProfile'])->findOrFail($id);
        return view('articles.article_detail', compact('article'));
    }

    public function store(Request $request) 
    {
        // 1. Validasi
        $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:100',
            'category'    => 'required|string|max:50',
            'content'     => 'required|string',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        
        $imageUrl = null;
        $imagePath = null;

        // 2. Upload Gambar
        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('articles', 'public');
            $imageUrl = '/storage/' . $imagePath;
        }

        // 3. Database Transaction (ACID)
        DB::beginTransaction(); 

        try {
            Article::create([
                'journalist_id' => Auth::id(),
                'title'         => $request->title,
                'author_name'   => $request->author,
                'category'      => $request->category,
                'image_url'     => $imageUrl,
                'content'       => $request->content,
            ]);

            DB::commit(); // Permanenkan data

            return redirect()->route('journalist.dashboard')
                             ->with('success', 'Artikel inspirasi berhasil dipublikasikan!');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan data jika gagal
            
            // Hapus gambar yang terlanjur ter-upload jika database gagal
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id) 
    {
        $article = Article::findOrFail($id);

        // Keamanan: Pastikan jurnalis hanya bisa mengedit artikel miliknya sendiri
        if ($article->journalist_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('journalist.edit_article', compact('article'));
    }

    // ==========================================
    // FUNGSI UPDATE ARTIKEL
    // ==========================================
    public function update(Request $request, $id) 
    {
        $article = Article::findOrFail($id);

        if ($article->journalist_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:100',
            'category'    => 'required|string|max:50',
            'content'     => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Nullable karena gambar tidak wajib diganti
        ]);

        DB::beginTransaction();

        try {
            $imageUrl = $article->image_url; // Default gunakan URL gambar lama
            $newImagePath = null;

            // Jika jurnalis mengunggah gambar baru
            if ($request->hasFile('cover_image')) {
                // 1. Hapus gambar lama dari storage server jika ada
                if ($article->image_url) {
                    $oldPath = str_replace('/storage/', '', $article->image_url);
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }

                // 2. Simpan gambar baru
                $newImagePath = $request->file('cover_image')->store('articles', 'public');
                $imageUrl = '/storage/' . $newImagePath;
            }

            // Update data ke database
            $article->update([
                'title'       => $request->title,
                'author_name' => $request->author,
                'category'    => $request->category,
                'image_url'   => $imageUrl,
                'content'     => $request->content,
            ]);

            DB::commit();
            return redirect()->route('journalist.dashboard')->with('success', 'Artikel berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus gambar baru jika database gagal diupdate
            if ($newImagePath && Storage::disk('public')->exists($newImagePath)) {
                Storage::disk('public')->delete($newImagePath);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat mengupdate: ' . $e->getMessage())->withInput();
        }
    }

    // ==========================================
    // FUNGSI DELETE ARTIKEL
    // ==========================================
    public function destroy($id) 
    {
        $article = Article::findOrFail($id);

        if ($article->journalist_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();

        try {
            // 1. Hapus gambar dari server sebelum menghapus data di database
            if ($article->image_url) {
                $oldPath = str_replace('/storage/', '', $article->image_url);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // 2. Hapus data dari database
            $article->delete();

            DB::commit();
            return redirect()->route('journalist.dashboard')->with('success', 'Artikel berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menghapus artikel: ' . $e->getMessage());
        }
    }

    public function authorProfile($id)
    {
        $author = \App\Models\User::with('journalistProfile')->findOrFail($id);
        $articles = \App\Models\Article::where('journalist_id', $id)->latest()->get();
        
        return view('customer.author_profile', compact('author', 'articles'));
    }
    
}