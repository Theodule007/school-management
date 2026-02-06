<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
        protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'status'
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function periods(): HasMany
    {
        return $this->hasMany(Period::class);
    }

    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class);
    }
}
