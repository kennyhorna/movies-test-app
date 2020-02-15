<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
