<?php

namespace Database\Seeders;

use App\Models\QueerIdentity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QueerIdentitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
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

        $rows = [];
        foreach ($names as $name) {
            $rows[] = ['id' => Str::ulid()->toBase32(), 'name' => $name];
        }

        QueerIdentity::insert($rows);
    }
}
