<?php

namespace Database\Seeders;

use App\Models\LevelSection;
use App\Models\Subject;
use App\Models\SubjectSection;
use Illuminate\Database\Seeder;

class SubjectSectionSeeder extends Seeder
{
    public function run(): void
    {
        $math = Subject::where('name', 'Mathematiques')->first();
        $phys = Subject::where('name', 'Physique')->first();

        $levelSections = LevelSection::all();

        foreach ($levelSections as $levelSection) {

            SubjectSection::create([
                'level_section_id' => $levelSection->id,
                'subject_id' => $math->id,
                'coefficient' => 4
            ]);

            SubjectSection::create([
                'level_section_id' => $levelSection->id,
                'subject_id' => $phys->id,
                'coefficient' => 3
            ]);
        }
    }
}