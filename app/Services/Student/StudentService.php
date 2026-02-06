<?php

use App\Models\Student;

class StudentService
{
    public function attachGuardians(Student $student, array $guardianIds)
    {
        if (count($guardianIds) !== 2) {
            throw new DomainException(
                'Un élève doit avoir exactement deux responsables.'
            );
        }

        $student->guardians()->sync($guardianIds);
    }
}
