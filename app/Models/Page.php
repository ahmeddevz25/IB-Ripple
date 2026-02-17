<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'slider_id',
        'event_ids',
        'page_title',
        'sub_title',
        'slug',
        'body',
        'is_active',
        'is_navbar',
        'sort_order',
    ];

    protected $casts = [
        'event_ids' => 'array',
    ];
    protected $dates = ['deleted_at']; // ensures proper timestamp casting

    public function mediaDocuments()
    {
        return $this->hasMany(MediaDocument::class, 'page_id'); // media_documents table me page_id add karna hoga
    }

    // Parent page
    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id')
            ->with('children');
    }
    // public function menus()
    // {
    //     return $this->belongsToMany(Menu::class, 'page_menu', 'page_id', 'menu_id')->withTimestamps();
    // }
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_page', 'page_id', 'menu_id')->withTimestamps();
    }

    public function slider()
    {
        return $this->belongsTo(Slider::class, 'slider_id');
    }

    public function sidebarParents()
    {
        return $this->belongsToMany(Page::class, 'page_sidebar', 'page_id', 'sidebar_page_id')->withTimestamps();
    }

    public function sidebarPages()
    {
        return $this->belongsToMany(Page::class, 'page_sidebar', 'sidebar_page_id', 'page_id')->withTimestamps();
    }
    public function sidebarChildren()
    {
        return $this->belongsToMany(Page::class, 'page_sidebar', 'sidebar_page_id', 'page_id')->withTimestamps();
    }
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
