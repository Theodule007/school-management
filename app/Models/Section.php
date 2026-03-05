<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_default'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_default' => 'boolean'
    ];

    public function levels(): BelongsToMany
    {
        return $this->belongsToMany(Level::class, 'level_sections');
    }

    // Pour accéder directement aux modèles LevelSection
    public function levelSections(): HasMany
    {
        return $this->hasMany(LevelSection::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_sections', 'level_section_id', 'subject_id')
            ->withPivot('coefficient')
            ->withTimestamps();
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
    /**
     * Obtenir le nombre d'étudiants actifs pour l'année en cours
     */
    public function getCurrentStudentsCountAttribute()
    {
        $currentYear = AcademicYear::where('status', 'active')->first();

        if (!$currentYear) {
            return 0;
        }

        return $this->levelSections()
            ->join('enrollments', 'level_sections.id', '=', 'enrollments.level_section_id')
            ->where('enrollments.academic_year_id', $currentYear->id)
            ->where('enrollments.status', 'enrolled')
            ->count();
    }
    /**
     * Obtenir les étudiants actifs pour l'année en cours
     */
    public function currentStudents()
    {
        $currentYear = AcademicYear::where('status', 'active')->first();

        if (!$currentYear) {
            return collect();
        }

        return $this->enrollments()
            ->with('student')
            ->where('academic_year_id', $currentYear->id)
            ->where('status', 'enrolled')
            ->get()
            ->pluck('student');
    }
}
