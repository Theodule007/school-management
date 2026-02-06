<?php

use App\Models\Grade;
use App\Models\ReportCard;
use App\Models\User;

class ReportCardService
{
    public function validate(ReportCard $reportCard, User $user)
    {
        if ($reportCard->validated_at) {
            throw new DomainException('Bulletin déjà validé.');
        }

        $reportCard->update([
            'validated_by' => $user->id,
            'validated_at' => now(),
        ]);

        Grade::where('enrollment_id', $reportCard->enrollment_id)
            ->where('period_id', $reportCard->period_id)
            ->update(['is_locked' => true]);
    }
}
