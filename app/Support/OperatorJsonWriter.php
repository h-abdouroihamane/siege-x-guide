<?php

namespace App\Support;

use App\Models\Operator;

class OperatorJsonWriter
{
    public static function write(): void
    {
        if (app()->runningUnitTests()) {
            return;
        }

        $operators = Operator::with([
            'squad' => fn ($q) => $q->select('squads.id', 'squads.name'),
            'roles:id,name',
            'queerIdentities:id,name',
            'secondaryGadgets:id,name',
        ])
            ->orderBy('name')
            ->get();

        $payload = $operators
            ->map(function (Operator $o) {
                $squad = $o->squad->first();

                return [
                    'name' => $o->name,
                    'description' => $o->description,
                    'side' => $o->side,
                    'year' => $o->year,
                    'season' => $o->season,
                    'operation_id' => $o->operation_id,
                    'squad' => $squad
                        ? [
                            'name' => $squad->name,
                            'rank' => (int) $squad->pivot->rank,
                        ]
                        : null,
                    'roles' => $o->roles
                        ->pluck('name')
                        ->sort()
                        ->values()
                        ->all(),
                    'queer_identities' => $o->queerIdentities
                        ->pluck('name')
                        ->sort()
                        ->values()
                        ->all(),
                    'secondary_gadgets' => $o->secondaryGadgets
                        ->pluck('name')
                        ->sort()
                        ->values()
                        ->all(),
                ];
            })
            ->values()
            ->all();

        file_put_contents(
            self::path(),
            json_encode(
                $payload,
                JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_UNICODE |
                    JSON_UNESCAPED_SLASHES,
            ).PHP_EOL,
        );
    }

    public static function path(): string
    {
        return base_path('database/data/operators.json');
    }
}
