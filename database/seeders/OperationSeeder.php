<?php
namespace Database\Seeders;

use App\Models\Operation;
use Illuminate\Database\Seeder;

class OperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seasonNames = [
            "Operation Black Ice",
            "Operation Dust Line",
            "Operation Skull Rain",
            "Operation Red Crow",
            "Operation Velvet Shell",
            "Operation Health",
            "Operation Blood Orchid",
            "Operation White Noise",
            "Operation Chimera",
            "Operation Para Bellum",
            "Operation Grim Sky",
            "Operation Wind Bastion",
            "Operation Burnt Horizon",
            "Operation Phantom Sight",
            "Operation Ember Rise",
            "Operation Shifting Tides",
            "Operation Void Edge",
            "Operation Steel Wave",
            "Operation Shadow Legacy",
            "Operation Neon Dawn",
            "Crimson Heist",
            "North Star",
            "Crystal Guard",
            "High Calibre",
            "Demon Veil",
            "Operation Vector Glare",
            "Operation Brutal Swarm",
            "Operation Solar Raid",
            "Operation Commanding Force",
            "Operation Dread Factor",
            "Operation Heavy Mettle",
            "Operation Deep Freeze",
            "Operation Deadly Omen",
            "Operation New Blood",
            "Operation Twin Shells",
            "Operation Collision Point",
            "Operation Prep Phase",
            "Operation Daybreak",
        ];

        //Special season for game launch
        $operationData = [
            [
                "year"   => 1,
                "season" => 0,
                "name"   => "Game Launch",
                "id"     => "Y1S0",
            ],
        ];

        $year   = 1;
        $season = 1;

        foreach ($seasonNames as $name) {
            $operationData[] = [
                "year"   => $year,
                "season" => $season,
                "name"   => $name,
                "id"     => "Y{$year}S{$season}",
            ];

            if ($season < 4) {
                $season++;
            } else {
                $year++;
                $season = 1;
            }
        }

        Operation::insert($operationData);
    }
}
