<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Operation;
use App\Models\Operator;
use App\Models\Rework;

class OperatorReworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reworkedOperators = [
            [
                "name" => "Tachanka",
                "operation_id" => "Y5S3"
            ],
            [
                "name" => "Striker",
                "operation_id" => "Y9S2"
            ],
            [
                "name" => "Sentry",
                "operation_id" => "Y9S2"
            ],
            [
                "name" => "Blackbeard",
                "operation_id" => "Y9S4"
            ],
            [
                "name" => "Clash",
                "operation_id" => "Y10S2"
            ],
            [
                "name" => "Thatcher",
                "operation_id" => "Y10S4"
            ]
        ];

        foreach ($reworkedOperators as $rop) {
            $operator = Operator::firstWhere('name', $rop['name']);
            $r = new Rework();
            $r->operator_id = $operator->id;
            $r->operation_id = $rop['operation_id'];
            $r->save();
        }

    }
}
