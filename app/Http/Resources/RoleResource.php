<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $operators = [];

        foreach ($this->operators as $op) {
            $operators[] = $op->name;
        }

        return ['name' => $this->name,
            'definition' => $this->definition,
            'operators'=> $operators];
    }
}
