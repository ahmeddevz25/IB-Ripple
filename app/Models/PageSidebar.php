<?php

namespace App\Models;

use App\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageSidebar extends Model
{
    use HasFactory;

    protected $table = 'page_sidebar';

    protected $fillable = [
        'page_id',
        'sidebar_page_id',
    ];

    // Optional relationships
    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function sidebarPage()
    {
        return $this->belongsTo(Page::class, 'sidebar_page_id');
    }
}

