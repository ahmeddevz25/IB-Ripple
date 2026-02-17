<?php

namespace App\Models;

use App\Models\Page;
use Illuminate\Database\Eloquent\Model;

class MediaDocument extends Model
{
    protected $fillable = [
        'page_id',
        'file_path',
        'thumbnail',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}
