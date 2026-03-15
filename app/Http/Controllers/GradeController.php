<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\SubjectSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
    $currentYear = AcademicYear::where('status', 'active')->first();

    $levelSectionId = $request->level_section_id;
    $periodId = $request->period_id;

    $enrollments = Enrollment::with('student')
        ->where('academic_year_id', $currentYear->id)
        ->where('level_section_id', $levelSectionId)
        ->get();

    $subjects = SubjectSection::with('subject')
        ->where('level_section_id', $levelSectionId)
        ->get();

    return view('grades.create', compact(
        'enrollments',
        'subjects',
        'periodId',
        'levelSectionId'
    ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $teacherId = Auth::user()?->teacher_id;
    foreach ($request->grades as $enrollmentId => $subjects) {

        foreach ($subjects as $subjectId => $value) {

            Grade::updateOrCreate(
                [
                    'enrollment_id' => $enrollmentId,
                    'subject_id' => $subjectId,
                    'period_id' => $request->period_id
                ],
                [
                    'value' => $value,
                    'teacher_id' => $teacherId
                ]
            );
        }
    }

    return back()->with('success', 'Notes enregistrées');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
