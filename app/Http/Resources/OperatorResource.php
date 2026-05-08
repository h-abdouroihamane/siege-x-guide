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

        $queerIdentities = [];

        foreach ($this->queerIdentities as $q) {
            $queerIdentities[] = $q->name;
        }

        $squadModel = $this->squad->first();
        $squad = $squadModel ? $squadModel->name : 'Unaffiliated';

        $hasRework = !is_null($this->rework);
        $operation = $this->getOperation();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'side' => $this->side,
            'year' => $operation->year,
            'season' => $operation->season,
            'reworked' => $hasRework,
            'operation' => [
                'id' => $operation->id,
                'name' => $operation->name,
                'release_date' => $operation->release_date,
            ],
            'roles' => $roles,
            'squad' => $squad,
            'queerIdentities' => $queerIdentities,
        ];
    }
}
