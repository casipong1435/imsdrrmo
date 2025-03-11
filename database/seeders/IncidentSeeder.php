<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IncidentType;


class IncidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $incident_types = [
            [
                'type' => 'Earthquake'
            ],
            [
                'type' => 'Tsunami'
            ],
            [
                'type' => 'Landslide'
            ],
            [
                'type' => 'Flood'
            ],
            [
                'type' => 'Tornado'
            ],
            [
                'type' => 'Fire'
            ],
            [
                'type' => 'Wildfire/Bushfire'
            ],
            [
                'type' => 'Others'
            ]
        ];

        IncidentType::insert($incident_types);
    }
}
