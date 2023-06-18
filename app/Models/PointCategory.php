<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PointCategory extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['title'];

    protected $hidden = ['points', 'updated_at'];

    protected $withCount = ['points'];

    protected $appends = ['image_url'];

    /**
     * @return HasMany
     */
    public function points(): HasMany
    {
        return $this->hasMany(Point::class, 'category_id');
    }

    public function image(): HasOneThrough
    {
        return $this->hasOneThrough(Image::class, PointCategoryImage::class, 'point_category_id', 'id', 'id', 'image_id');
    }

    public function getImageUrlAttribute()
    {
        return $this->getFirstMediaUrl();
    }
}
