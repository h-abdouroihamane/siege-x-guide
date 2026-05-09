<?php

namespace Database\Seeders;

use App\Models\SecondaryGadget;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SecondaryGadgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gadgets = [
            'Gonne-6' => 'Attack',
            'Breach Charge' => 'Attack',
            'Smoke Grenade' => 'Attack',
            'Stun Grenade' => 'Attack',
            'Frag Grenade' => 'Attack',
            'Claymore' => 'Attack',
            'Hard Breach Charge' => 'Attack',
            'Impact EMP Grenade' => 'Attack',
            'Deployable Shield' => 'Defense',
            'Barbed Wire' => 'Defense',
            'C4' => 'Defense',
            'Impact Grenade' => 'Defense',
            'Bulletproof Camera' => 'Defense',
            'Proximity Alarm' => 'Defense',
            'Observation Blocker' => 'Defense',
        ];

        foreach ($gadgets as $name => $side) {
            SecondaryGadget::create([
                'id' => Str::ulid()->toBase32(),
                'name' => $name,
                'side' => $side,
            ]);
        }
    }
}
