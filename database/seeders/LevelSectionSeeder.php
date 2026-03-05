<?php
namespace Database\Seeders;

use App\Models\Level;
use App\Models\LevelSection;
use App\Models\Section;
use Illuminate\Database\Seeder;

class LevelSectionSeeder extends Seeder
{
    public function run(): void
    {
        $ns3 = Level::where('name', 'NS3')->first();

        $sections = Section::whereIn('name', ['SVT','SMP','SES','LLA'])->get();

        foreach ($sections as $section) {
            LevelSection::create([
                'level_id' => $ns3->id,
                'section_id' => $section->id
            ]);
        }
    }
}