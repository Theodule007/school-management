<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        Level::insert([
            [
                'name' => '7ème AF',
                'cycle' => 'fondamentale',
            ],
            [
                'name' => 'NS3',
                'cycle' => 'secondaire',
            ]
        ]);
    }
}
