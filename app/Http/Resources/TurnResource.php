<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed schedule
 * @property mixed status
 */
class TurnResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'schedule' => $this->schedule,
            'status' => $this->when(auth()->check(), (boolean) $this->status),
        ];
    }
}
