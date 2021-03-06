<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'owner' => $this->whenLoaded('owner', function () {
                return new UserResource($this->owner->user);
            }),
            'status' => $this->whenLoaded('owner', function () {
                return $this->owner->status ?? "Not Available Yet";
            }),
            'members' => $this->whenLoaded('members', function () {
                return $this->members->map(function ($member) {
                    return new UserResource($member->user);
                });
            }),
            'created_at' => $this->created_at->format('Y-m-d'),
            'user_house_id' => $this->whenLoaded('houseUsers', function () {
                return $this->houseUsers->first()->id ?? null;
            }),
        ];
    }
}
