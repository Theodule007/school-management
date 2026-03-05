<?php

namespace App\Http\Controllers;

use AcademicYearService;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->paginate(10);
        return view('academic-years.index', compact('academicYears'));
    }

    public function create()
    {
        return view('academic-years.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:draft,active,closed'
        ]);

        AcademicYear::create($validated);

        return redirect()->route('academic-years.index')
            ->with('success', 'Année académique créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicYear $academicYear)
    {
        return view('academic-years.show', compact('academicYear'));
    }

    public function edit(AcademicYear $academicYear)
    {
        return view('academic-years.edit', compact('academicYear'));
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:draft,active,closed'
        ]);

        $academicYear->update($validated);

        return redirect()->route('academic-years.index')
            ->with('success', 'Année académique mise à jour avec succès.');
    }

    public function destroy(AcademicYear $academicYear)
    {
        $academicYear->delete();

        return redirect()->route('academic-years.index')
            ->with('success', 'Année académique supprimée avec succès.');
    }

    public function activate(
        AcademicYear $year,
        AcademicYearService $service
    ) {
        $service->activate($year);

        return back();
    }
}
