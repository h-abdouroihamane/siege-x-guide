<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SecondaryGadgetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $operators = $this->operators
            ->sort(function ($a, $b) {
                return $a->compareReleaseDate($b);
            })
            ->pluck('name');

        return ['name' => $this->name, 'operators' => $operators];
    }
}
