<?php

namespace App\Http\Controllers\Journalist;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
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
}