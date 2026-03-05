<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use Illuminate\Http\Request;

class GuardianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Guardian::with(['students' => function ($q) {
            $q->withPivot(['relationship', 'is_primary']);
        }])
            ->withCount('students');

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$search}%"]);
            });
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $guardians = $query->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(15)
            ->withQueryString();

        return view('guardians.index', compact('guardians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //return view('guardians.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255|unique:guardians,email',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        Guardian::create($validated);

        return redirect()->route('guardians.index')
            ->with('success', 'Tuteur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Guardian $guardian)
    {
        $guardian->load(['students' => function ($query) {
            $query->withPivot(['relationship', 'is_primary']);
                //->with('current_level');
        }]);

        return view('guardians.show', compact('guardian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guardian $guardian)
    {
        return view('guardians.edit', compact('guardian'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guardian $guardian)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255|unique:guardians,email,' . $guardian->id,
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        $guardian->update($validated);

        return redirect()->route('guardians.index')
            ->with('success', 'Tuteur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guardian $guardian)
    {
        // Vérifier si le tuteur a des étudiants associés
        if ($guardian->students()->count() > 0) {
            return redirect()->route('guardians.index')
                ->with('error', 'Impossible de supprimer ce tuteur car il est associé à des étudiants.');
        }

        $guardian->delete();

        return redirect()->route('guardians.index')
            ->with('success', 'Tuteur supprimé avec succès.');
    }
}
