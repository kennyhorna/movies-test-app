<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getPaginationInfo(LengthAwarePaginator $paginatedCollection): array
    {
        return [
            'links' => [
                'first' => $paginatedCollection->url(1),
                'last' => $paginatedCollection->url($paginatedCollection->lastPage()),
                'next' => $paginatedCollection->nextPageUrl(),
                'prev' => $paginatedCollection->previousPageUrl(),
            ],
            'meta' => [
                'current_page' => $paginatedCollection->currentPage(),
                'from' => $paginatedCollection->firstItem(),
                'to' => $paginatedCollection->lastItem(),
                'total' => $paginatedCollection->total(),
                'last_page' => $paginatedCollection->lastPage(),
                'per_page' => $paginatedCollection->perPage(),
            ],
        ];
    }
}
