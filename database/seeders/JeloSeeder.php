<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jelo;
class JeloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jelo::create([
            'naziv_jela'=>'Plata suhomesnatih proizvoda',
            'cena'=>5500.00,
            'opis'=>'Idealna plata za proslave'
        ]);

        Jelo::create([
            'naziv_jela'=>'Pileci stapici sa susamom',
            'cena'=>550.00,
            'opis'=>'Hrskavi obrok'
        ]);
        Jelo::create([
            'naziv_jela'=>'Lovacka snicla',
            'cena'=>550.00,
            'opis'=>'Kremasta i ukusna'
        ]);
        Jelo::create([
            'naziv_jela'=>'Lazanja bolonjeze',
            'cena'=>550.00,
            'opis'=>'Italijanski specijalitet'
        ]);
        Jelo::create([
            'naziv_jela'=>'Penne carbonara',
            'cena'=>550.00,
            'opis'=>'Najbolja karbonara'
        ]);
        Jelo::create([
            'naziv_jela'=>'Dimljeni batak',
            'cena'=>550.00,
            'opis'=>'Svez ukus dima'
        ]);
        Jelo::create([
            'naziv_jela'=>'Becka snicla',
            'cena'=>550.00,
            'opis'=>'Kultno jelo za svaki dan'
        ]);


    }
}
