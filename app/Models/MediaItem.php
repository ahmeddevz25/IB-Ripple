<?php
namespace App\Models;

use App\Models\Event;
use App\Models\MediaImage;
use App\Models\MediaVideo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaItem extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'type'];

    public function images()
    {
        return $this->hasMany(MediaImage::class, 'media_item_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function videos()
    {
        return $this->hasMany(MediaVideo::class, 'media_item_id');
    }

}
