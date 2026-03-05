<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubjectSection extends Model
{
    protected $table = 'subject_sections';

    protected $fillable = [
        'subject_id',
        'level_section_id',
        'coefficient'
    ];

    protected $casts = [
        'coefficient' => 'integer'
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function levelSection(): BelongsTo
    {
        return $this->belongsTo(LevelSection::class);
    }
}
