<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointImage extends Model
{
    protected $table = 'point_images';

    public $timestamps = false;

    public function point(): BelongsTo
    {
        return $this->belongsTo(Point::class, 'point_id');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'image_id');
    }
}
