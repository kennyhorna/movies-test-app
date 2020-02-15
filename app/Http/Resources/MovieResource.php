<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name'         => $this->name,
            'release_date' => $this->release_date,
            'status'       => (bool) $this->status,
            'image'        => $this->image,
            'turns'        => $this->whenLoaded('turns', TurnResource::collection($this->turns)),
        ];
    }
}
