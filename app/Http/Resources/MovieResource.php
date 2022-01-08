<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $release_date
 * @property mixed $name
 * @property mixed $status
 * @property mixed $image
 * @property mixed $turns
 */
class MovieResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'release_date' => $this->release_date,
            'status' => (bool)$this->status,
            'image' => $this->image,
            'turns' => $this->whenLoaded('turns', TurnResource::collection($this->turns)),
        ];
    }
}
