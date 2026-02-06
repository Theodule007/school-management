<?php

use App\Models\AcademicYear;

class AcademicYearService
{
    public function activate(AcademicYear $year)
    {
        AcademicYear::where('status', 'active')
            ->update(['status' => 'closed']);

        $year->update(['status' => 'active']);
    }

    public function ensureActive()
    {
        if (!AcademicYear::where('status', 'active')->exists()) {
            throw new DomainException('Aucune année académique active.');
        }
    }
}
