<?php
namespace App\Models;

use App\Models\MediaItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MediaImage extends Model
{
    use HasFactory;
    protected $fillable = ['media_item_id', 'title', 'file_path'];

    public function mediaItem()
    {
        return $this->belongsTo(MediaItem::class);
    }

}
