<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Turn extends Model
{
    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->whereStatus(true);
    }

    public function scopeIncludeInactiveForAdmins($query, $is_admin)
    {
        return $query->when( ! $is_admin, function ($query, $is_admin) {
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
        return in_array(Str::lower($field), ['id', 'schedule', 'status']);
    }

    private function acceptableSortingMode($mode)
    {
        return in_array(Str::lower($mode), ['asc', 'desc']);
    }
}
