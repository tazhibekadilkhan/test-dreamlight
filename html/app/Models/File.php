<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['name', 'name_original', 'size', 'extension', 'path', 'thumbnail'];

    public function getPathAttribute(): string
    {
        return '/storage/' . $this->attributes['path'];
    }

    public function getThumbnailAttribute(): string
    {
        return '/storage/' . $this->attributes['thumbnail'];
    }

    public function getSizeAttribute(): string
    {
        return $this->attributes['size'] . 'MB';
    }
}
