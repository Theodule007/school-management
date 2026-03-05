<?php

use App\Models\Enrollment;
use App\Models\Student;

class EnrollmentService
{
    public function enroll(
        Student $student,
        int $academicYearId,
        int $levelSectionId
    ) {
        if (Enrollment::where('student_id', $student->id)
            ->where('academic_year_id', $academicYearId)
            ->exists()) {
            throw new Exception("Déjà inscrit pour cette année.");
        }

        return Enrollment::create([
            'student_id' => $student->id,
            'academic_year_id' => $academicYearId,
            'level_section_id' => $levelSectionId
        ]);
    }

    public function changeSection(Enrollment $enrollment, $newLevelSectionId)
    {
        if ($enrollment->academicYear->status === 'active') {
            throw new Exception(
                "Impossible de changer de section pendant l'année."
            );
        }

        $enrollment->update([
            'level_section_id' => $newLevelSectionId
        ]);
    }
}