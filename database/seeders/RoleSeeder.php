<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Breach' => 'Attackers whose main focus is to create holes in reinforced or soft surfaces for more lines of sight on the objective',
            'Anti-Gadget' => "Operators who can destroy/disable/deny the other team's gadgets",
            'Intel' => "Operators specialized in getting the position of the enemy's team operators and relaying that intel to their teammates",
            'Trapper' => "Defenders who come equipped with traps that can hinder the attackers' push if they are careless",
            'Front line' => 'Attackers that will lead the push and engage defenders first',
            'Map control' => 'Attackers whose job is to take over a key part of the map and prevent defenders from taking it back',
            'Anti-Entry' => "Defenders whose primary task is to delay the attackers' push with their gadget",
            'Support' => 'Operators supporting their team through the use of their gadget',
            'Crowd control' => 'Defenders whose gadget/ability directly or indirectly influences where the attackers can push',
        ];

        foreach ($roles as $name => $definition) {
            Role::create([
                'id' => Str::ulid()->toBase32(),
                'name' => $name,
                'definition' => $definition,
            ]);
        }
    }
}
