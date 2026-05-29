<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\SecondaryGadget;
use App\Models\Operator;

class SecondaryGadgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gadgets = [
            'Gonne-6' => [
                'Glaz',
                'Capitão',
                'Dokkaebi',
                'Amaru',
                'Zero',
                'Iana',
            ],
            'Breach Charge' => [
                'Thatcher',
                'Ash',
                'Fuze',
                'Blitz',
                'IQ',
                'Hibana',
                'Nomad',
                'Kali',
                'Rauora',
                'Striker',
                'Solid Snake',
            ],
            'Smoke Grenade' => [
                'Thermite',
                'Twitch',
                'Montagne',
                'Glaz',
                'Fuze',
                'Blitz',
                'Jackal',
                'Ying',
                'Dokkaebi',
                'Finka',
                'Maverick',
                'Gridlock',
                'Kali',
                'Iana',
                'Brava',
                'Ram',
                'Rauora',
                'Striker',
                'Solid Snake',
            ],
            'Stun Grenade' => [
                'Sledge',
                'Thermite',
                'Buck',
                'Hibana',
                'Lion',
                'Finka',
                'Maverick',
                'Nomad',
                'Amaru',
                'Flores',
                'Ram',
                'Striker',
                'Ace',
                'Solid Snake',
            ],
            'Frag Grenade' => [
                'Sledge',
                'Glaz',
                'IQ',
                'Blackbeard',
                'Lion',
                'Finka',
                'Gridlock',
                'Nøkk',
                'Osa',
                'Sens',
                'Deimos',
                'Striker',
                'Maverick',
                'Jackal',
                'Solid Snake',
            ],
            'Claymore' => [
                'Thatcher',
                'Ash',
                'Twitch',
                'Glaz',
                'IQ',
                'Buck',
                'Blackbeard',
                'Capitão',
                'Jackal',
                'Zofia',
                'Lion',
                'Maverick',
                'Kali',
                'Ace',
                'Zero',
                'Flores',
                'Osa',
                'Sens',
                'Grim',
                'Brava',
                'Striker',
                'Hibana',
            ],
            'Hard Breach Charge' => [
                'Montagne',
                'Fuze',
                'Capitão',
                'Ying',
                'Zofia',
                'Nøkk',
                'Amaru',
                'Zero',
                'Sens',
                'Grim',
                'Deimos',
                'Striker',
            ],
            'Impact EMP Grenade' => [
                'Sledge',
                'Montagne',
                'Capitão',
                'Nøkk',
                'Dokkaebi',
                'Gridlock',
                'Iana',
                'Osa',
                'Grim',
                'Striker',
                'Solid Snake',
            ],
            'Deployable Shield' => [
                'Pulse',
                'Tachanka',
                'Frost',
                'Echo',
                'Ela',
                'Warden',
                'Thunderbird',
                'Thorn',
                'Sentry',
                'Denari',
                'Smoke',
            ],
            'Barbed Wire' => [
                'Smoke',
                'Doc',
                'Kapkan',
                'Tachanka',
                'Bandit',
                'Ela',
                'Maestro',
                'Clash',
                'Kaid',
                'Mozzie',
                'Oryx',
                'Aruni',
                'Thunderbird',
                'Thorn',
                'Azami',
                'Sentry',
            ],
            'C4' => [
                'Mute',
                'Rook',
                'Pulse',
                'Bandit',
                'Valkyrie',
                'Mira',
                'Kaid',
                'Mozzie',
                'Warden',
                'Tubarão',
                'Sentry',
            ],
            'Impact Grenade' => [
                'Rook',
                'Valkyrie',
                'Caveira',
                'Echo',
                'Ela',
                'Vigil',
                'Maestro',
                'Clash',
                'Mozzie',
                'Goyo',
                'Wamai',
                'Melusi',
                'Azami',
                'Skopós',
                'Sentry',
            ],
            'Bulletproof Camera' => [
                'Mute',
                'Castle',
                'Doc',
                'Kapkan',
                'Jäger',
                'Frost',
                'Lesion',
                'Vigil',
                'Goyo',
                'Melusi',
                'Aruni',
                'Thunderbird',
                'Solis',
                'Fenrir',
                'Sentry',
            ],
            'Proximity Alarm' => [
                'Smoke',
                'Castle',
                'Rook',
                'Tachanka',
                'Caveira',
                'Mira',
                'Alibi',
                'Goyo',
                'Wamai',
                'Oryx',
                'Solis',
                'Tubarão',
                'Skopós',
                'Sentry',
            ],
            'Observation Blocker' => [
                'Pulse',
                'Jäger',
                'Caveira',
                'Lesion',
                'Maestro',
                'Alibi',
                'Kaid',
                'Warden',
                'Fenrir',
                'Denari',
                'Sentry',
            ],
        ];

        foreach ($gadgets as $gadget_name => $operators) {
            $firstOperator = Operator::firstWhere('name', $operators[0]);

            $g = SecondaryGadget::create([
                'id' => Str::ulid()->toBase32(),
                'name' => $gadget_name,
                'side' => $firstOperator->side,
            ]);

            foreach ($operators as $op_name) {
                $operator = Operator::firstWhere('name', $op_name);

                if (!$operator) {
                    echo "Could not find '${op_name} in '${gadget_name}'";
                    return;
                }

                $g->operators()->attach($operator);
            }
        }
    }
}
