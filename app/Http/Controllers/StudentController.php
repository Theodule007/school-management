<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use StudentService;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Student::paginate(20);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, StudentService $service)
    {
        $student = Student::create($request->only([
            'first_name',
            'last_name',
            'gender',
            'birth_date'
        ]));

        $service->attachGuardians(
            $student,
            $request->guardian_ids
        );

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return $student;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $student->update($request->all());

        return redirect()->route('students.index');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return back();
    }
}
