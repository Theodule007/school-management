<?php

namespace App\Policies;

use App\Models\ReportCard;
use App\Models\User;

class ReportCardPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function validate(User $user, ReportCard $reportCard)
    {
        return $user->hasRole('direction')
            && is_null($reportCard->validated_at);
    }
}
