<?php

namespace Database\Seeders;

use App\Models\Operator;
use App\Models\Squad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SquadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
    "Wolfguard" => [
        "Doc",
        "Twitch",
        "Montagne",
        "Lion",
        "Bandit",
        "Castle",
        "Ying",
        "Clash",
        "Nomad",
        "Melusi",
        "Frost",
        "Thunderbird",
        "Sens",
        "Tubarão",
        "Skopós",
    ],

    "Ghosteyes" => [
        "Caveira",
        "Zero",
        "Glaz",
        "Valkyrie",
        "Vigil",
        "Zofia",
        "Lesion",
        "Nøkk",
        "Warden",
        "Maverick",
        "Mozzie",
        "Iana",
        "Flores",
        "Solis",
    ],

    "Redhammer" => [
        "Thermite",
        "Ash",
        "Tachanka",
        "Fuze",
        "Kapkan",
        "Sledge",
        "Buck",
        "Blackbeard",
        "Kaid",
        "Amaru",
        "Goyo",
        "Gridlock",
        "Oryx",
        "Thorn",
        "Fenrir",
        "Ram",
    ],

    "Viperstrike" => [
        "Hibana",
        "Thatcher",
        "Mute",
        "Rook",
        "Blitz",
        "Jäger",
        "Capitão",
        "Echo",
        "Jackal",
        "Mira",
        "Dokkaebi",
        "Maestro",
        "Alibi",
        "Azami",
        "Brava",
        "Rauora",
    ],

    "Nighthaven" => [
        "Kali",
        "Osa",
        "Wamai",
        "Ace",
        "Aruni",
        "Ela",
        "Smoke",
        "Finka",
        "IQ",
        "Pulse",
        "Grim",
    ],
];

        foreach ($data as $squad_name => $operators) {
            $squad = Squad::create(['id' => Str::ulid()->toBase32(), 'name' => $squad_name]);

            foreach ($operators as $op_name) {
                $operator = Operator::firstWhere('name', $op_name);

                if (!$operator) {
                    throw new \RuntimeException("Migration stopped - can't find '$op_name'");
                }

                $operator->squad()->attach($squad->id);
            }
        }
    }
}
