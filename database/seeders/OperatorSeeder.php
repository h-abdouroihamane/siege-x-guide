<?php

namespace Database\Seeders;

use App\Models\Operator;
use App\Models\QueerIdentity;
use App\Models\Role;
use App\Models\SecondaryGadget;
use App\Models\Squad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OperatorSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $path = base_path('database/data/operators.json');
        $rows = json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);

        $rolesByName = Role::pluck('id', 'name');
        $squadsByName = Squad::pluck('id', 'name');
        $identitiesByName = QueerIdentity::pluck('id', 'name');
        $gadgetsByName = SecondaryGadget::pluck('id', 'name');

        foreach ($rows as $row) {
            $operator = Operator::create([
                'id' => Str::ulid()->toBase32(),
                'name' => $row['name'],
                'description' => $row['description'],
                'side' => $row['side'],
                'year' => $row['year'],
                'season' => $row['season'],
                'operation_id' => $row['operation_id'],
            ]);

            if (
                ! empty($row['squad']) &&
                isset($squadsByName[$row['squad']['name']])
            ) {
                $operator->squad()->attach(
                    $squadsByName[$row['squad']['name']],
                    ['rank' => $row['squad']['rank']],
                );
            }

            $resolve = fn (array $names, $lookup) => collect($names)
                ->map(fn ($n) => $lookup[$n] ?? null)
                ->filter()
                ->values()
                ->all();

            $operator->roles()->attach($resolve($row['roles'] ?? [], $rolesByName));
            $operator->queerIdentities()->attach(
                $resolve($row['queer_identities'] ?? [], $identitiesByName),
            );
            $operator->secondaryGadgets()->attach(
                $resolve($row['secondary_gadgets'] ?? [], $gadgetsByName),
            );
        }
    }
}
