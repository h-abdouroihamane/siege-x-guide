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
        $roles = $this->roles->pluck('name')->all();

        $queerIdentities = $this->queerIdentities->pluck('name')->all();

        $squadModel = $this->squad->first();
        $squad = $squadModel ? $squadModel->name : 'Unaffiliated';

        $hasRework = !is_null($this->rework);
        $operation = $this->getOperation();
        [$year, $season] = $this->resource->sortableYearSeason();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'side' => $this->side,
            'year' => $year,
            'season' => $season,
            'reworked' => $hasRework,
            'operation' => [
                'id' => $operation->id,
                'name' => $operation->name,
                'release_date' => $operation->release_date,
            ],
            'roles' => $roles,
            'squad' => $squad,
            'queerIdentities' => $queerIdentities,
            'secondaryGadgetIds' => $this->whenLoaded(
                'secondaryGadgets',
                fn() => $this->secondaryGadgets->pluck('id')->all(),
            ),
        ];
    }
}
