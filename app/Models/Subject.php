<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
        protected $fillable = ['name', 'status'];

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'subject_section')
            ->withPivot('coefficient')
            ->withTimestamps();
    }
}
