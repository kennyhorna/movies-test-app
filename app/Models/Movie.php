<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @method self includeInactiveForAdmins(bool $isAdmin)
 * @method self orderByField(array $fields)
 * @method LengthAwarePaginator paginate(int $pageSize)
 * @method string image()
 * @mixin Eloquent
 * @mixin Builder
 */
class Movie extends Model
{

    protected $guarded = [];

    public function turns(): BelongsToMany
    {
        return $this->belongsToMany(Turn::class);
    }

    public function getImageUrlAttribute(): string
    {
        return Storage::disk('movie_files')->url($this->attributes['image']);
    }

    public function scopeActive($query)
    {
        return $query->whereStatus(true);
    }

    public function scopeIncludeInactiveForAdmins($query, $isAdmin)
    {
        return $query->when(! $isAdmin, function ($query, $isAdmin) {
            return $query->active();
        });
    }

    public function scopeOrderByField($query, $sorting)
    {
        $sorting['mode'] = $sorting['mode'] ?? 'asc';
        $sorting['field'] = $sorting['order_by'] ?? 'release_date';
        if ($this->sortingValidation($sorting)) {
            $query->orderBy($sorting['field'], $sorting['mode']);
        }
    }

    private function sortingValidation($sorting): bool
    {
        return $this->acceptableSortingField($sorting['field'])
            && $this->acceptableSortingMode($sorting['mode']);
    }

    private function acceptableSortingField($field): bool
    {
        return in_array(Str::lower($field), ['id', 'name', 'release_date', 'status']);
    }

    private function acceptableSortingMode($mode): bool
    {
        return in_array(Str::lower($mode), ['asc', 'desc']);
    }
}
