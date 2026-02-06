<?php

use App\Models\Enrollment;

class PaymentService
{
    public function store(Enrollment $enrollment, array $data)
    {
        if ($enrollment->academicYear->status !== 'active') {
            throw new DomainException('Année académique clôturée.');
        }

        return $enrollment->payments()->create($data);
    }
}
