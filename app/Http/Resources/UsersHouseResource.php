<?php

namespace App\Http\Resources;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersHouseResource extends JsonResource
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
            'user' => UserResource::make($this->user),
            'house' => HouseResource::make($this->house),
        ];
    }
}