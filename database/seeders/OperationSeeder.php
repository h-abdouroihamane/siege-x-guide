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
            'Operation Black Ice',
            'Operation Dust Line',
            'Operation Skull Rain',
            'Operation Red Crow',
            'Operation Velvet Shell',
            'Operation Health',
            'Operation Blood Orchid',
            'Operation White Noise',
            'Operation Chimera',
            'Operation Para Bellum',
            'Operation Grim Sky',
            'Operation Wind Bastion',
            'Operation Burnt Horizon',
            'Operation Phantom Sight',
            'Operation Ember Rise',
            'Operation Shifting Tides',
            'Operation Void Edge',
            'Operation Steel Wave',
            'Operation Shadow Legacy',
            'Operation Neon Dawn',
            'Crimson Heist',
            'North Star',
            'Crystal Guard',
            'High Calibre',
            'Demon Veil',
            'Operation Vector Glare',
            'Operation Brutal Swarm',
            'Operation Solar Raid',
            'Operation Commanding Force',
            'Operation Dread Factor',
            'Operation Heavy Mettle',
            'Operation Deep Freeze',
            'Operation Deadly Omen',
            'Operation New Blood',
            'Operation Twin Shells',
            'Operation Collision Point',
            'Operation Prep Phase',
            'Operation Daybreak',
            'Operation High Stakes',
            'Operation Tenfold Pursuit',
            'Operation Silent Hunt',
        ];

        // Special season for game launch
        $operationData = [
            [
                'year' => 1,
                'season' => 0,
                'name' => 'Game Launch',
                'id' => 'Y1S0',
                'release_date' => '2015-12-01',
            ],
        ];

        $releaseDates = [
            '2016-02-01',
            '2016-05-01',
            '2016-08-01',
            '2016-11-01',
            '2017-02-01',
            '2017-05-01',
            '2017-08-01',
            '2017-11-01',
            '2018-03-01',
            '2018-06-01',
            '2018-09-01',
            '2018-12-01',
            '2019-03-01',
            '2019-06-01',
            '2019-09-01',
            '2019-12-01',
            '2020-03-01',
            '2020-06-01',
            '2020-09-01',
            '2020-12-01',
            '2021-03-01',
            '2021-06-01',
            '2021-09-01',
            '2021-11-01',
            '2022-03-01',
            '2022-06-01',
            '2022-09-01',
            '2022-12-01',
            '2023-03-01',
            '2023-05-01',
            '2023-08-01',
            '2023-11-01',
            '2024-03-01',
            '2024-06-01',
            '2024-09-01',
            '2024-12-01',
            '2025-03-01',
            '2025-06-01',
            '2025-09-02',
            '2025-12-02',
            '2026-03-03',
        ];

        $year = 1;
        $season = 1;

        foreach ($seasonNames as $index => $name) {
            $operationData[] = [
                'year' => $year,
                'season' => $season,
                'name' => $name,
                'id' => "Y{$year}S{$season}",
                'release_date' => $releaseDates[$index],
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
