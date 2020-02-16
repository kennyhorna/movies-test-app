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

    /**
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $paginated_collection
     * @return array
     */
    public function getPaginationInfo($paginated_collection)
    {
        return [
            'links' => [
                'first' => $paginated_collection->url(1),
                'last'  => $paginated_collection->url($paginated_collection->lastPage()),
                'next'  => $paginated_collection->nextPageUrl(),
                'prev'  => $paginated_collection->previousPageUrl(),
            ],
            'meta'  => [
                'current_page' => $paginated_collection->currentPage(),
                'from'         => $paginated_collection->firstItem(),
                'to'           => $paginated_collection->lastItem(),
                'total'        => $paginated_collection->total(),
                'last_page'    => $paginated_collection->lastPage(),
                'per_page'     => $paginated_collection->perPage(),
            ],
        ];
    }
}
