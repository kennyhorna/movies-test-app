<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Movie extends Model {

    protected $guarded = [];

    public function turns()
    {
        return $this->belongsToMany(Turn::class);
    }

    public function getImageAttribute()
    {
        return Storage::disk('movie_files')->url($this->attributes['image']);
    }

    public function scopeActive($query)
    {
        return $query->whereStatus(true);
    }

    public function scopeIncludeInactiveForAdmins($query, $is_admin)
    {
        return $query->when(! $is_admin, function ($query, $is_admin) {
            return $query->active();
        });
    }

    public function scopeOrderByField($query, $sorting)
    {
        $sorting['mode'] = isset($sorting['mode']) ? $sorting['mode'] : 'asc';
        $sorting['field'] = isset($sorting['order_by']) ? $sorting['order_by'] : 'release_date';


        if ($this->sortingValidation($sorting))
        {
            $query->orderBy($sorting['field'], $sorting['mode']);
        }
    }

    private function sortingValidation($sorting)
    {
        return $this->acceptableSortingField($sorting['field'])
            && $this->acceptableSortingMode($sorting['mode']);
    }

    private function acceptableSortingField($field)
    {
        return in_array(Str::lower($field), ['id', 'name', 'release_date', 'status']);
    }

    private function acceptableSortingMode($mode)
    {
        return in_array(Str::lower($mode), ['asc', 'desc']);
    }
}
