<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    /**
     * Menampilkan detail artikel untuk dibaca Customer
     */
    public function show($id)
    {
        // 1. Ambil data artikel + Gabungkan dengan tabel profil jurnalis buat ngambil foto!
        $article = DB::table('articles')
            ->leftJoin('journalist_profiles', 'articles.journalist_id', '=', 'journalist_profiles.user_id')
            ->select('articles.*', 'journalist_profiles.profile_image')
            ->where('articles.id', $id)
            ->first();

        // Jika artikel tidak ditemukan, kembalikan halaman 404
        if (!$article) {
            abort(404);
        }

        // 2. Tambah 1 ke kolom views_count setiap kali dibuka
        DB::table('articles')->where('articles.id', $id)->increment('views_count');

 // 3. Tampilkan ke halaman baca artikel (Customer)
        return view('customer.article_detail', compact('article'));
    }

    /**
     * Menampilkan Profil Jurnalis (Author) dari sisi Customer
     */
    public function authorProfile($id)
    {
        // 1. Ambil data author (gabungan tabel users dan journalist_profiles)
        $author = DB::table('users')
            ->leftJoin('journalist_profiles', 'users.id', '=', 'journalist_profiles.user_id')
            ->where('users.id', $id)
            ->select('users.name', 'journalist_profiles.bio', 'journalist_profiles.profile_image', 'journalist_profiles.header_image')
            ->first();

        // Jika author tidak ditemukan, kembalikan halaman 404
        if (!$author) {
            abort(404);
        }

        // 2. Ambil semua artikel yang pernah dipublish oleh author ini
        $articles = DB::table('articles')
            ->where('journalist_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Tampilkan ke halaman author profile
        return view('customer.author_profile', compact('author', 'articles'));
    }
}