<?php
namespace App\Models;

use App\Models\MediaItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'event_date', 'thumbnail'];
    protected $casts = [
        'event_date' => 'date',
    ];

    public function mediaItems()
    {
        return $this->hasMany(MediaItem::class, 'event_id');
    }

}
