<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Section;
use App\Services\Level\LevelSectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LevelController extends Controller
{
    protected $levelSectionService;

    public function __construct(LevelSectionService $levelSectionService)
    {
        $this->levelSectionService = $levelSectionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Construction de la requête
        $query = Level::withCount('sections');

        // Filtre de recherche
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Exécutio++---n de la requête - paginate() retourne un objet LengthAwarePaginator
        $levels = $query->orderBy('name')
            ->orderBy('cycle')
            ->paginate(10)
            ->withQueryString();

        return view('levels.index', compact('levels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::orderBy('name')->get();
        return view('levels.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:levels,name',
            'cycle' => 'required|in:fondamentale,secondaire',
            'sections' => 'sometimes|array',
            'sections.*' => 'exists:sections,id',
        ]);

        DB::transaction(function () use ($validated) {
            $level = Level::create([
                'name' => $validated['name'],
                'cycle' => $validated['cycle']
            ]);

            if (!empty($validated['sections'])) {
                foreach ($validated['sections'] as $sectionId) {
                    $section = Section::find($sectionId);
                    $this->levelSectionService->attachSectionToLevel($level, $section);
                }
            }
        });
        return redirect()->route('levels.index')
            ->with('success', 'Niveau créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Level $level)
    {
        $level->load(['sections']);

        // Récupérer toutes les combinaisons niveau-section pour ce niveau
        $levelSections = $this->levelSectionService->getLevelSectionsByLevel($level);

        return view('levels.show', compact('level', 'levelSections'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Level $level)
    {
        $level->load('sections');
        $sections = Section::orderBy('name')->get();
        $selectedSections = $level->sections->pluck('id')->toArray();

        return view('levels.edit', compact('level', 'sections', 'selectedSections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Level $level)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:levels,name,' . $level->id,
            'cycle' => 'required|in:fondamentale,secondaire',
            'sections' => 'sometimes|array',
            'sections.*' => 'exists:sections,id'
        ]);

        DB::transaction(function () use ($validated, $level) {
            $level->update([
                'name' => $validated['name'],
                'cycle' => $validated['cycle']
            ]);

            // Synchroniser les sections
            $level->sections()->sync($validated['sections'] ?? []);
        });

        return redirect()->route('levels.show', $level)
            ->with('success', 'Niveau mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Level $level)
    {
        // Vérifier s'il y a des inscriptions via level_section
        $hasEnrollments = $level->sections()
            ->whereHas('levelSection.enrollments')
            ->exists();

        if ($hasEnrollments) {
            return redirect()->route('levels.index')
                ->with('error', 'Impossible de supprimer ce niveau car des inscriptions y sont liées.');
        }

        $level->delete();

        return redirect()->route('levels.index')
            ->with('success', 'Niveau supprimé avec succès.');
    }
}
