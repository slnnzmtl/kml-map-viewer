<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Forestry extends Model
{
    protected $table = 'forestry';

    protected $fillable = ['name'];

    protected $hidden = ['geo'];

    protected $withCount = ['points'];


    public function points(): HasMany
    {
        return $this->hasMany(Point::class);
    }

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }
}
