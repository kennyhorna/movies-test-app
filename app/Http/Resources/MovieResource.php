<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $release_date
 * @property mixed $name
 * @property mixed $status
 * @property mixed $image_url
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
            'image' => $this->image_url,
            'turns' => $this->whenLoaded('turns', TurnResource::collection($this->turns)),
        ];
    }
}
