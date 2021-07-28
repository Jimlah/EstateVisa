<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HouseResource extends JsonResource
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
            'estate' => $this->estate->name,
            'house_type_id' => $this->house_type->name,
            'code' =>  $this->code,
            'description' => $this->description,
            'created_at' => $this->create_at,
            'updated_at' => $this->updated_at,
        ];
    }
}