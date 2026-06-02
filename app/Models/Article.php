<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    // Mengizinkan kolom-kolom ini diisi secara massal melalui Controller
    protected $fillable = [
        'journalist_id',
        'title',
        'author_name',
        'category',
        'content',
        'image_url',
    ];
}