<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EstateHouseResource extends JsonResource
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
            'estate_name' => $this->whenLoaded('estate', function () {
                return $this->estate->name;
            }),
            'house_name' => $this->whenLoaded('house', function () {
                return $this->house->name;
            }),
            'house_type' => $this->whenLoaded('house', function () {
                return $this->house->houseType->name;
            }),
            'house_description' => $this->whenLoaded('house', function () {
                return $this->house->description;
            }),
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
