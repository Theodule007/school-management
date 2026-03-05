<?php
// app/Services/LevelSectionService.php

namespace App\Services\Level;

use App\Models\Level;
use App\Models\LevelSection;
use App\Models\Section;
use Illuminate\Support\Facades\DB;

class LevelSectionService
{
    /**
     * Associer un niveau à une section
     */
    public function attachSectionToLevel(Level $level, Section $section)
    {
        return LevelSection::firstOrCreate([
            'level_id' => $level->id,
            'section_id' => $section->id
        ]);
    }

    /**
     * Obtenir toutes les combinaisons niveau-section disponibles
     */
    public function getAllLevelSections()
    {
        return LevelSection::with(['level', 'section'])->get();
    }

    /**
     * Obtenir les combinaisons niveau-section par niveau
     */
    public function getLevelSectionsByLevel(Level $level)
    {
        return LevelSection::where('level_id', $level->id)
            ->with('section')
            ->get();
    }

    /**
     * Obtenir les combinaisons niveau-section par section
     */
    public function getLevelSectionsBySection(Section $section)
    {
        return LevelSection::where('section_id', $section->id)
            ->with('level')
            ->get();
    }
}