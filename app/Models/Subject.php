<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = ['name'];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    // protected $fillable = ['name', 'status'];
    // protected $casts = [
    //     'status' => 'string',
    //     'created_at' => 'datetime',
    //     'updated_at' => 'datetime'
    // ];

    public function levelSections()
    {
        return $this->belongsToMany(LevelSection::class, 'subject_sections')
            ->withPivot('coefficient');
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'subject_sections', 'subject_id', 'level_section_id')
            ->withPivot('coefficient');
    }
    /**
     * Une matière a plusieurs notes
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Scope pour les matières actives
     */
    // public function scopeActive($query)
    // {
    //     return $query->where('status', 'active');
    // }
}
