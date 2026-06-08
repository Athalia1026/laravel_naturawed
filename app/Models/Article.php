<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'journalist_id',
        'title',
        'author_name',
        'category',
        'image_url',
        'content',
    ];
}
