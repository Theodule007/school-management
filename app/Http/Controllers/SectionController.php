<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\LevelSection;
use App\Models\Section;
use App\Services\Level\LevelSectionService;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    protected $levelSectionService;

    public function __construct(LevelSectionService $levelSectionService)
    {
        $this->levelSectionService = $levelSectionService;
    }

    public function index(Request $request)
    {
        $query = Section::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('is_default')) {
            $query->where('is_default', $request->is_default);
        }

        $sections = $query->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('sections.index', compact('sections'));
    }

    public function create()
    {
        $levels = Level::orderBy('cycle')->orderBy('name')->get();
        return view('sections.create', compact('levels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Section::create([
            'name' => $validated['name'],
            'is_default' => $validated['is_default'] ?? false
        ]);
        return redirect()->route('sections.index')
            ->with('success', 'Section créée avec succès.');
    }

    public function show(Section $section)
    {

        // Charger les relations nécessaires
        $section->load('levels', 'levelSections');

        // Récupérer les combinaisons niveau-section avec leurs sujets
        $levelSections = LevelSection::with(['level', 'subjects'])
            ->where('section_id', $section->id)
            ->get();

        return view('sections.show', compact('section', 'levelSections'));
        //    $section->first();

        //     // Récupérer toutes les combinaisons niveau-section pour cette section
        //     $levelSections = $this->levelSectionService->getLevelSectionsBySection($section);
        //     return view('sections.show', compact('section', 'levelSections'));
    }

    public function edit(Section $section)
    {
        $section->load('levels');
        $levels = Level::orderBy('cycle')->orderBy('name')->get();
        $selectedLevels = $section->levels->pluck('id')->toArray();

        return view('sections.edit', compact('section', 'levels', 'selectedLevels'));
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_default' => 'boolean'
        ]);

        // Convertir la checkbox en boolean
        $validated['is_default'] = $request->has('is_default');

        $section->update($validated);

        return redirect()->route('sections.show', $section)
            ->with('success', 'Section mise à jour avec succès.');
    }

    public function destroy(Section $section)
    {
        // Vérifier s'il y a des étudiants liés
        if ($section->students()->count() > 0) {
            return redirect()->route('sections.index')
                ->with('error', 'Impossible de supprimer cette section car des étudiants y sont liés.');
        }

        // Vérifier s'il y a des inscriptions via level_section
        $hasEnrollments = $section->levelSection()
            ->whereHas('enrollments')
            ->exists();

        if ($hasEnrollments) {
            return redirect()->route('sections.index')
                ->with('error', 'Impossible de supprimer cette section car des inscriptions y sont liées.');
        }
        $section->delete();

        return redirect()->route('sections.index')
            ->with('success', 'Section supprimée avec succès.');
    }
}
