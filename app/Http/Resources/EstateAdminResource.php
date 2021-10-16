<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EstateAdminResource extends JsonResource
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
            'name' => $this->whenLoaded('user.profile', function () {
                return $this->user->profile->firstname . ' ' . $this->user->profile->lastname;
            }),
            'email' => $this->whenLoaded('user', function () {
                return $this->user->email;
            }),
            'status' => $this->stat,
            'is_owner' => $this->is_owner,
            'estate' => $this->whenLoaded('estate', function () {
                return $this->estate->name;
            }),
            'created_at' => $this->created_at,
        ];
    }
}
