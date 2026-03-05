<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    protected $fillable = ['name'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'level_sections');
    }

    // Pour accéder directement aux modèles LevelSection
    public function levelSections()
    {
        return $this->hasMany(LevelSection::class);
    }

    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class);
    }

    public function students()
    {
        return $this->hasManyThrough(
            Student::class,
            Enrollment::class,
            'level_section_id', // Clé étrangère sur enrollments (vers section)
            'id',         // Clé locale sur students
            'id',         // Clé locale sur levels
            'student_id'  // Clé étrangère sur enrollments (vers student)
        )->join('level_sections', 'enrollments.level_section_id', '=', 'level_sections.id')
            ->where('level_sections.level_id', $this->id);
    }

    /**
     * Étudiants pour une année spécifique
     */
    public function studentsForYear($academicYearId)
    {
        return $this->students()
            ->where('enrollments.academic_year_id', $academicYearId);
    }
}
