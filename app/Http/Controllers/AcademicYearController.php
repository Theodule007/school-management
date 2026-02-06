<?php

namespace App\Http\Controllers;

use AcademicYearService;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index()
    {
        return AcademicYear::all();
    }

    public function create()
    {
        return view('academic-years.create');
    }

    public function store(Request $request)
    {
        AcademicYear::create($request->all());

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicYear $academicYear)
    {
        return $academicYear;
    }

    public function edit(AcademicYear $academicYear)
    {
        return view('academic-years.edit', compact('academicYear'));
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $academicYear->update($request->all());

        return back();
    }

    public function destroy(AcademicYear $academicYear)
    {
        $academicYear->delete();

        return back();
    }

    public function activate(
        AcademicYear $year,
        AcademicYearService $service
    ) {
        $service->activate($year);

        return back();
    }
}
