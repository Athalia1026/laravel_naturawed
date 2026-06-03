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
        // 1. Ambil data artikel yang mau dibaca
        $article = DB::table('articles')->where('id', $id)->first();

        // Jika artikel tidak ditemukan, kembalikan halaman 404
        if (!$article) {
            abort(404);
        }

        // 2. LOGIKA SAKTI: Tambah 1 ke kolom views_count setiap kali dibuka!
        DB::table('articles')->where('id', $id)->increment('views_count');

        // 3. Tampilkan ke halaman baca artikel (Customer)
        // Pastikan kamu sudah punya file blade-nya nanti, misal: resources/views/customer/article_detail.blade.php
        return view('customer.article_detail', compact('article'));
    }
}