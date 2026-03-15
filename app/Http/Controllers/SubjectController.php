<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\LevelSection;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{

    public function index(Request $request)
    {
        $query = Subject::with(['levelSections' => function ($q) {
            $q->with(['level', 'section']);
        }])
            ->withCount('grades');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        // if ($request->filled('status')) {
        //     $query->where('status', $request->status);
        // }

        $subjects = $query->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        // Récupérer toutes les combinaisons niveau-section pour l'affectation
        $levelSections = LevelSection::with(['level', 'section'])->get();
        $levels = Level::all();
        $sections = Section::all();

        return view('subjects.create', compact('levelSections', 'levels', 'sections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name',
            //'status' => 'required|in:active,inactive',
            'level_sections' => 'sometimes|array',
            'level_sections.*.level_id' => 'required|exists:levels,id',
            'level_sections.*.section_id' => 'required|exists:sections,id',
            'level_sections.*.coefficient' => 'required|numeric|min:0.5|max:10'
        ]);

        DB::transaction(function () use ($validated) {
            $subject = Subject::create([
                'name' => $validated['name'],
                // 'status' => $validated['status']
            ]);

            if (!empty($validated['level_sections'])) {
                $pivotData = [];
                foreach ($validated['level_sections'] as $ls) {
                   // $pivotData[$ls['id']] = ['coefficient' => $ls['coefficient']];
                    $levelSection = LevelSection::firstOrCreate([
                    'level_id' => $ls['level_id'],
                    'section_id' => $ls['section_id']
                ]);
                     $subject->levelSections()->attach($levelSection->id, [
                    'coefficient' => $ls['coefficient']
                ]);
                }
               // $subject->levelSections()->sync($pivotData);
            }
        });

        return redirect()->route('subjects.index')
            ->with('success', 'Matière créée avec succès.');
    }

    public function show(Subject $subject)
    {
        $subject->load(['levelSections.level', 'levelSections.section', 'grades.enrollment.student']);

        // Regrouper les level_sections par niveau pour l'affichage
        $groupedByLevel = $subject->levelSections->groupBy('level_id');

        return view('subjects.show', compact('subject', 'groupedByLevel'));
    }

    public function edit(Subject $subject)
    {
        $subject->load('levelSections');

        $levelSections = LevelSection::with(['level', 'section'])->get();
        $levels = Level::all();
        $sections = Section::all();

        // Préparer les données des level_sections déjà associées
        $selectedLevelSections = $subject->levelSections->mapWithKeys(function ($ls) {
            return [$ls->id => ['coefficient' => $ls->pivot->coefficient]];
        })->toArray();

        return view('subjects.edit', compact('subject', 'levelSections', 'levels', 'sections', 'selectedLevelSections'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
            // 'status' => 'required|in:active,inactive',
            'level_sections' => 'sometimes|array',
            'level_sections.*.id' => 'required|exists:level_sections,id',
            'level_sections.*.coefficient' => 'required|numeric|min:0.5|max:10'
        ]);

        DB::transaction(function () use ($validated, $subject) {
            $subject->update([
                'name' => $validated['name'],
                //'status' => $validated['status']
            ]);

            if (!empty($validated['level_sections'])) {
                $pivotData = [];
                foreach ($validated['level_sections'] as $ls) {
                    $pivotData[$ls['id']] = ['coefficient' => $ls['coefficient']];
                }
                $subject->levelSections()->sync($pivotData);
            } else {
                $subject->levelSections()->detach();
            }
        });

        return redirect()->route('subjects.show', $subject)
            ->with('success', 'Matière mise à jour avec succès.');
    }

    public function destroy(Subject $subject)
    {
        if ($subject->grades()->count() > 0) {
            return redirect()->route('subjects.index')
                ->with('error', 'Impossible de supprimer cette matière car des notes lui sont associées.');
        }

        $subject->levelSections()->detach();
        $subject->delete();

        return redirect()->route('subjects.index')
            ->with('success', 'Matière supprimée avec succès.');
    }

    /**
     * API: Récupérer les level_sections par niveau et/ou section
     */
    public function getLevelSections(Request $request)
    {
        $query = LevelSection::with(['level', 'section']);

        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        return response()->json($query->get());
    }
}
