<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\QueerIdentity;
use App\Models\Operator;

class QueerIdentitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $queerIdentities = [
            'Aroace',
            'Aromantic',
            'Asexual',
            'Bisexual',
            'Gay',
            'Intersex',
            'Lesbian',
            'Non-binary',
            'Pansexual',
            'Transgender',
        ];

        $data = [];

        foreach ($queerIdentities as $q) {
            $data[] = ['id' => Str::ulid()->toBase32(), 'name' => $q];
        }

        QueerIdentity::insert($data);

        //Linking operators to their queer identity
        $queerOps = [
            [
                'name' => 'Caveira',
                'queerIdentity' => 'Lesbian',
            ],
            [
                'name' => 'Flores',
                'queerIdentity' => 'Gay',
            ],
            [
                'name' => 'Osa',
                'queerIdentity' => 'Transgender',
            ],
            [
                'name' => 'Pulse',
                'queerIdentity' => 'Bisexual',
            ],
            [
                'name' => 'Sens',
                'queerIdentity' => 'Non-Binary',
            ],
            [
                'name' => 'Tubarão',
                'queerIdentity' => 'Transgender',
            ],
        ];

        foreach ($queerOps as $q) {
            $op = Operator::firstWhere('name', $q['name']);
            $identity = QueerIdentity::firstWhere('name', $q['queerIdentity']);
            $op->queerIdentities()->attach($identity);
        }
    }
}
