<?php

namespace Database\Seeders;

use App\Models\Operator;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Breach' => [
                'Ace',
                'Ash',
                'Blackbeard',
                'Buck',
                'Hibana',
                'Maverick',
                'Ram',
                'Sledge',
                'Thermite',
                'Zofia',
            ],
            'Anti-Gadget' => [
                'Ace',
                'Aruni',
                'Bandit',
                'Brava',
                'Flores',
                'Fuze',
                'Jäger',
                'Kaid',
                'Kali',
                'Maestro',
                'Mozzie',
                'Mute',
                'Ram',
                'Sledge',
                'Thatcher',
                'Tubarão',
                'Twitch',
                'Vigil',
                'Wamai',
                'Warden',
                'Zero',
                'Zofia',
            ],
            'Intel' => [
                'Alibi',
                'Brava',
                'Caveira',
                'Clash',
                'Deimos',
                'Dokkaebi',
                'Echo',
                'Flores',
                'Glaz',
                'Iana',
                'IQ',
                'Jackal',
                'Lion',
                'Maestro',
                'Melusi',
                'Mira',
                'Montagne',
                'Mozzie',
                'Osa',
                'Pulse',
                'Skopós',
                'Solis',
                'Twitch',
                'Valkyrie',
                'Warden',
                'Zero',
            ],
            'Trapper' => [
                'Alibi',
                'Ela',
                'Fenrir',
                'Frost',
                'Goyo',
                'Kapkan',
                'Lesion',
                'Smoke',
                'Thorn',
                'Wamai',
            ],
            'Front line' => [
                'Amaru',
                'Ash',
                'Blackbeard',
                'Blitz',
                'Capitão',
                'Finka',
                'Grim',
                'Hibana',
                'Iana',
                'Maverick',
                'Nøkk',
                'Nomad',
                'Ying',
            ],
            'Map control' => [
                'Amaru',
                'Blitz',
                'Capitão',
                'Deimos',
                'Dokkaebi',
                'Gridlock',
                'Grim',
                'Jackal',
                'Lion',
                'Nøkk',
                'Nomad',
                'Rauora',
                'Sens',
                'Ying',
            ],
            'Anti-Entry' => [
                'Aruni',
                'Azami',
                'Bandit',
                'Castle',
                'Denari',
                'Frost',
                'Goyo',
                'Kaid',
                'Kapkan',
                'Lesion',
                'Smoke',
                'Tachanka',
                'Thorn',
                'Tubarão',
            ],
            'Support' => [
                'Azami',
                'Buck',
                'Castle',
                'Doc',
                'Finka',
                'Glaz',
                'Gridlock',
                'IQ',
                'Jäger',
                'Kali',
                'Mira',
                'Montagne',
                'Oryx',
                'Osa',
                'Pulse',
                'Rauora',
                'Rook',
                'Sens',
                'Sentry',
                'Skopós',
                'Solis',
                'Striker',
                'Thatcher',
                'Thunderbird',
                'Thermite',
                'Valkyrie',
            ],
            'Crowd control' => [
                'Caveira',
                'Clash',
                'Denari',
                'Echo',
                'Ela',
                'Fenrir',
                'Melusi',
                'Mute',
                'Tachanka',
                'Vigil',
            ],
        ];

        $definitions = [
            "Anti-Entry" => "Defenders whose primary task is to delay the attackers' push with their gadget",
            "Anti-Gadget" => "Operators who can destroy/disable/deny the other team's gadgets",
            "Breach" => "Attackers whose main focus is to create holes in reinforced or soft surfaces for more lines of sight on the objective",
            "Crowd control" => "Defenders whose gadget/ability directly or indirectly influences where the attackers can push",
            "Front line" => "Attackers that will lead the push and engage defenders first",
            "Intel" => "Operators specialized in getting the position of the enemy's team operators and relaying that intel to their teammates",
            "Map control" => "Attackers whose job is to take over a key part of the map and prevent defenders from taking it back",
            "Support" => "Operators supporting their team through the use of their gadget",
            "Trapper" => "Defenders who come equipped with traps that can hinder the attackers' push if they are careless"
        ];

        foreach ($data as $role_name => $operators) {
            $role = Role::create(['id' => Str::ulid()->toBase32(), 'name' => $role_name, 'definition' => $definitions[$role_name]]);

            foreach ($operators as $op_name) {
                $operator = Operator::firstWhere('name', $op_name);

                if (!$operator) {
                    echo "Could not find '${op_name}'";
                    return;
                }

                $operator->roles()->attach($role);
            }
        }


    }
}
