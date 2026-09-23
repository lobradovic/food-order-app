<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Status;
class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::insert([
            ['naziv_statusa' => 'Evidentirana'],
            ['naziv_statusa' => 'Prihvacena'],
            ['naziv_statusa' => 'U izradi'],
            ['naziv_statusa' => 'Otkazana'],
            ['naziv_statusa' => 'Zavrsena'],
        ]);
    }
}
