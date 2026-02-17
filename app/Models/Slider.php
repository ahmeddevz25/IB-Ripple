<?php
namespace App\Models;

use App\Models\SliderImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['title', 'is_active'];

    protected $dates    = ['deleted_at']; // ensures proper timestamp casting

    public function images()
    {
        return $this->hasMany(SliderImage::class);
    }
}
