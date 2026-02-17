<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
    ];

    // Relationship: a menu has many pages
    public function pages()
    {
        return $this->belongsToMany(Page::class, 'menu_page', 'menu_id', 'page_id')
                    ->withTimestamps();
    }

}
