<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EstateResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'logo' => $this->logo,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d'),
            'owner' => $this->whenLoaded('admins', function () {
                $user =  $this->admins->first()?->user;
                return UserResource::make($user);
            }),
        ];
    }
}
