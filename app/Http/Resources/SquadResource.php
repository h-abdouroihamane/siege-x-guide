<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SquadResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $operators = [];

        foreach ($this->operators as $op) {
            $operators[] = $op->name;
        }

        return [$this->name => $operators];
    }
}
