<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Movie extends Model {

    protected $guarded = [];

    public function turns()
    {
        return $this->belongsToMany(Turn::class);
    }

    public function setImageAttribute($image)
    {
        $this->attributes['image'] = Storage::disk('movie_files')->put('', $image);
    }

    public function getImageAttribute()
    {
        return Storage::disk('movie_files')->url($this->attributes['image']);
    }
}
