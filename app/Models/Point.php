<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Point extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['title', 'geo', 'description', 'view_360_link', 'online_cam_link', 'direction_link'];

    protected $hidden = ['category_id', 'forestry_id', 'updated_at'];
    protected $appends = ['images_count'];

    public function images(): HasManyThrough
    {
        return $this->hasManyThrough(Image::class, PointImage::class, 'point_id', 'id', null, 'image_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PointCategory::class, 'category_id');
    }

    public function forestry(): BelongsTo
    {
        return $this->belongsTo(Forestry::class, 'forestry_id');
    }


    public function getImagesCountAttribute()
    {
        return $this->getMedia()->count();
    }

//    public function getImagesUrlAttribute()
//    {
//        return $this->getMedia();
//    }
}
