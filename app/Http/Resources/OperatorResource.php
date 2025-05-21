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
        $operation = $this->operation()->first();
        return [
            'name' => $this->name,
            'description' => $this->description,
            'side' => $this->side,
            'year' => $operation->year,
            'season' => $operation->season,
            'operation_name' => $operation->name,
            'roles' => $this->getRoles(),
            'squad' => $this->getSquad()
        ];
    }
}
