<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barangay;

class BarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tangub_city_barangay = [
            [
                'barangay_name' => 'Aquino'
            ],
            [
                'barangay_name' => 'Balatacan'
            ],
            [
                'barangay_name' => 'Baluc'
            ],
            [
                'barangay_name' => 'Banglay'
            ],
            [
                'barangay_name' => 'Bintana'
            ],
            [
                'barangay_name' => 'Bocator'
            ],
            [
                'barangay_name' => 'Bongabong'
            ],
            [
                'barangay_name' => 'Caniangan'
            ],
            [
                'barangay_name' => 'Capalaran'
            ],
            [
                'barangay_name' => 'Catagan'
            ],
            [
                'barangay_name' => 'Barangay I - City Hall (Poblacion)'
            ],
            [
                'barangay_name' => 'Barangay II - Marilou Annex (Poblacion)'
            ],
            [
                'barangay_name' => 'Barangay III- Market Kalubian (Poblacion)'
            ],
            [
                'barangay_name' => 'Barangay IV - St. Michael (Poblacion)'
            ],
            [
                'barangay_name' => 'Barangay V - Malubog (Poblacion)'
            ],
            [
                'barangay_name' => 'Barangay VI - Lower Polao (Poblacion)'
            ],
            [
                'barangay_name' => 'Barangay VII - Upper Polao (Poblacion)'
            ],
            [
                'barangay_name' => 'Hoyohoy'
            ],
            [
                'barangay_name' => 'Isidro D. Tan (Dimalooc)'
            ],
            [
                'barangay_name' => 'Garang'
            ],
            [
                'barangay_name' => 'Guinabot'
            ],
            [
                'barangay_name' => 'Guinalaban'
            ],
            [
                'barangay_name' => 'Kausawagan'
            ],
            [
                'barangay_name' => 'Kimat'
            ],
            [
                'barangay_name' => 'Labuyo'
            ],
            [
                'barangay_name' => 'Lorenzo Tan'
            ],
            [
                'barangay_name' => 'Lumban'
            ],
            [
                'barangay_name' => 'Maloro'
            ],
            [
                'barangay_name' => 'Manga'
            ],
            [
                'barangay_name' => 'Mantic'
            ],
            [
                'barangay_name' => 'Maquilao'
            ],
            [
                'barangay_name' => 'Matugnao'
            ],
            [
                'barangay_name' => 'Migcanaway'
            ],
            [
                'barangay_name' => 'Minsubong'
            ],
            [
                'barangay_name' => 'Owayan'
            ],
            [
                'barangay_name' => 'Paiton'
            ],
            [
                'barangay_name' => 'Panalsalan'
            ],
            [
                'barangay_name' => 'Pangabuan'
            ],
            [
                'barangay_name' => 'Prenza'
            ],
            [
                'barangay_name' => 'Salimpuno'
            ],
            [
                'barangay_name' => 'San Antonio'
            ],
            [
                'barangay_name' => 'San Apolinario'
            ],
            [
                'barangay_name' => 'San Vicente'
            ],
            [
                'barangay_name' => 'Santa Cruz'
            ],
            [
                'barangay_name' => 'Santa Maria (Baga)'
            ],
            [
                'barangay_name' => 'Santo Niño'
            ],
            [
                'barangay_name' => 'Sicot'
            ],
            [
                'barangay_name' => 'Silanga'
            ],
            [
                'barangay_name' => 'Silangit'
            ],
            [
                'barangay_name' => 'Simasay'
            ],
            [
                'barangay_name' => 'Sumirap'
            ],
            [
                'barangay_name' => 'Taguite'
            ],
            [
                'barangay_name' => 'Tituron'
            ],
            [
                'barangay_name' => 'Tugas'
            ],
            [
                'barangay_name' => 'Villaba'
            ],

        ];

        Barangay::insert($tangub_city_barangay);
    }
}
