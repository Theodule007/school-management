<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'nisu',
        'first_name',
        'last_name',
        'gender',
        'birth_date',
        'section_id',
        'status'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function guardians(): BelongsToMany
    {
        return $this->belongsToMany(Guardian::class, 'student_guardian')
            ->withPivot(['relationship', 'is_primary'])
            ->withTimestamps();
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    /**
     * Obtenir l'inscription pour une année académique donnée
     */
    public function enrollmentForYear($academicYearId)
    {
        return $this->enrollments()
            ->where('academic_year_id', $academicYearId)
            ->first();
    }
    public function getCurrentLevelAttribute()
    {
          $currentYear = AcademicYear::where('status', 'active')->first();
            if (!$currentYear) {
            return null;

        }
        $enrollment = $this->enrollmentForYear($currentYear->id);
        return $enrollment?->levelSection?->level;
    }
    /**
     * Obtenir la section actuelle de l'étudiant (pour l'année en cours)
     */
    public function getCurrentSectionAttribute()
    {
        $currentYear = AcademicYear::where('status', 'active')->first();

        if (!$currentYear) {
            return null;
        }

        $enrollment = $this->enrollmentForYear($currentYear->id);

        return $enrollment ? $enrollment->levelSection->section : null;
    }

    /**
     * Obtenir le tuteur principal
     */
    public function getPrimaryGuardianAttribute()
    {
        return $this->guardians()
            ->wherePivot('is_primary', true)
            ->first();
    }

    /**
     * Nom complet
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Scope pour les étudiants actifs
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
