<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Level::all() as $level) {
            Section::create(['name' => 'A', 'level_id' => $level->id]);
            Section::create(['name' => 'B', 'level_id' => $level->id]);
        }
    }
}
