<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportCardController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Routes protégées par l'authentification (middleware 'auth')
Route::middleware(['auth'])->group(function () {
    // Tableau de bord
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('academic-years', AcademicYearController::class);
    Route::resource('levels', LevelController::class);
    Route::resource('sections', SectionController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('guardians', GuardianController::class);

    // Route pour la création groupée
    Route::post(
        '/students/store-with-guardians',
        [StudentController::class, 'storeWithGuardians']
    )
        ->name('students.store-with-guardians');
    Route::resource('students', StudentController::class);

    // API interne pour charger les sections par année
    Route::get(
        '/api/sections-by-level/{levelId}',
        [StudentController::class, 'getSectionsByLevel']
    )
        ->name('api.sections.by-year');

    Route::put('/students/{student}/guardians', [StudentController::class, 'updateGuardians'])
        ->name('students.guardians.update');

    // routes/web.php
    Route::get(
        '/api/level-sections-by-section/{sectionId}',
        [StudentController::class, 'getLevelSectionsBySection']
    )
        ->name('api.level-sections.by-section');

Route::get('/api/level-sections/{level_id?}/{section_id?}', [SubjectController::class, 'getLevelSections'])
    ->name('api.level-sections');
});
Route::get('/', function () {
    return view('welcome');
});
Route::resource('payments', PaymentController::class);
Route::resource('report-cards', ReportCardController::class);
Route::resource('enrollments', EnrollmentController::class);
Route::resource('grades', GradeController::class);
Route::resource('fees', FeeController::class);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
