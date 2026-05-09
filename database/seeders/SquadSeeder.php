<?php

namespace Database\Seeders;

use App\Models\Squad;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SquadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $squads = [
            'Wolfguard',
            'Ghosteyes',
            'Redhammer',
            'Viperstrike',
            'Nighthaven',
            'Unaffiliated',
        ];

        foreach ($squads as $index => $name) {
            Squad::create([
                'id' => Str::ulid()->toBase32(),
                'name' => $name,
                'rank' => $index + 1,
            ]);
        }
    }
}
