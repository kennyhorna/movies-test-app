<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed schedule
 * @property mixed status
 * @property int id
 */
class TurnResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'schedule' => $this->schedule,
            'status' => $this->when(auth()->check(), (boolean)$this->status),
        ];
    }
}
