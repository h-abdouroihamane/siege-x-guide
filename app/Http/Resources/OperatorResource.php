<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperatorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $roles = [];

        foreach ($this->roles as $r) {
            $roles[] = $r->name;
        }

        $squadModel = $this->squad->first();
        $squad = $squadModel ? $squadModel->name : "Unaffiliated";

        return [
            'name' => $this->name,
            'description' => $this->description,
            'side' => $this->side,
            'year' => $this->year,
            'season' => $this->season,
            'operation' => [
                'name' => $this->operation->name,
                'release_date' => $this->operation->release_date
            ],
            'roles' => $roles,
            'squad' => $squad
        ];
    }
}
