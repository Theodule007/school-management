<?php

namespace App\Models;

use App\Models\Level;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LevelSection extends Model
{
    protected $table = 'level_sections';

    protected $fillable = [
        'level_id',
        'section_id'
    ];
    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_sections')
            ->withPivot('coefficient');
    }
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
}
