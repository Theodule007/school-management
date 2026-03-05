<?php
namespace App\Services\SubjectSection;

use App\Models\LevelSection;

class SubjectSectionService
{
    public function assignSubjects(
        LevelSection $levelSection,
        array $subjects
    ) {
        foreach ($subjects as $subjectId => $coefficient) {
            $levelSection->subjects()->syncWithoutDetaching([
                $subjectId => ['coefficient' => $coefficient]
            ]);
        }
    }
}