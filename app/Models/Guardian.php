<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Guardian extends Model
{
        protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'address',
        'status'
    ];

        protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_guardian')
            ->withPivot(['relationship', 'is_primary'])
            ->withTimestamps();
    }
        /**
     * Obtenir le nom complet
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Obtenir le nombre d'étudiants associés
     */
    public function getStudentsCountAttribute(): int
    {
        return $this->students()->count();
    }

    /**
     * Scope pour les tuteurs actifs
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
