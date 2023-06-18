<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointCategoryImage extends Model
{
    protected $table = 'point_category_images';

    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(PointCategory::class, 'point_category_id');
    }

    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }
}
