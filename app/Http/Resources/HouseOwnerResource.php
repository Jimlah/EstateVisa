<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HouseOwnerResource extends JsonResource
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
            'estate_name' => $this->whenLoaded('estate',function () {
                return $this->estate->name;
            }),
            'status' => $this->status,
            'name' => $this->whenLoaded('user', function () {
                return $this->user->profile->firstname;
            }),

        ];
    }
}
