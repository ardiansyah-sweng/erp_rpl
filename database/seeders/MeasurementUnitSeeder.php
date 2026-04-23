<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MeasurementUnit;
use Faker\Factory as Faker;

class MeasurementUnitSeeder extends Seeder
{
    public function __construct()
    {
        $this->faker = Faker::create('id_ID');
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $col = config('db_constants.column.mu');
    
        MeasurementUnit::insert([
            [
                $col['unit'] => 'Bal',
                $col['abbr'] => 'Bal',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Batang',
                $col['abbr'] => 'Btg',
                $col['created'] => now(),
                $col['updated'] => now()
            ],            
            [
                $col['unit'] => 'Botol',
                $col['abbr'] => 'Btl',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Bungkus',
                $col['abbr'] => 'Bks',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Butir',
                $col['abbr'] => 'Btr',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Box',
                $col['abbr'] => 'Box',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Drum',
                $col['abbr'] => 'Drm',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Dus',
                $col['abbr'] => 'Dus',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Galon',
                $col['abbr'] => 'Gln',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Gram',
                $col['abbr'] => 'Gr',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Gross',
                $col['abbr'] => 'Grs',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Karton',
                $col['abbr'] => 'Ktn',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Karung',
                $col['abbr'] => 'Krg',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Kontainer',
                $col['abbr'] => 'Ktr',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Ikat',
                $col['abbr'] => 'Ikt',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Kaleng',
                $col['abbr'] => 'Klg',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Kilogram',
                $col['abbr'] => 'Kg',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Kodi',
                $col['abbr'] => 'Kd',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Krat',
                $col['abbr'] => 'Krt',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Kuintal',
                $col['abbr'] => 'Ktl',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Lusin',
                $col['abbr'] => 'Lsn',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Lembar',
                $col['abbr'] => 'Lbr',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Liter',
                $col['abbr'] => 'Lbr',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Miligram',
                $col['abbr'] => 'Mg',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Mililiter',
                $col['abbr'] => 'Ml',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Ons',
                $col['abbr'] => 'Ons',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Orang',
                $col['abbr'] => 'Org',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Pack',
                $col['abbr'] => 'Pck',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Pallet',
                $col['abbr'] => 'Plt',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Pieces',
                $col['abbr'] => 'Pcs',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Resep',
                $col['abbr'] => 'Rsp',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Rim',
                $col['abbr'] => 'Rim',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Sachet',
                $col['abbr'] => 'Sht',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Slop',
                $col['abbr'] => 'Slp',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Strip',
                $col['abbr'] => 'Str',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Tim',
                $col['abbr'] => 'Tim',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Ton',
                $col['abbr'] => 'Ton',
                $col['created'] => now(),
                $col['updated'] => now()
            ],
            [
                $col['unit'] => 'Unit',
                $col['abbr'] => 'Unt',
                $col['created'] => now(),
                $col['updated'] => now()
            ]
        ]);
    }
}
