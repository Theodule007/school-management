<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportCardController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::resource('students', StudentController::class);
Route::resource('academic-years', AcademicYearController::class);
Route::resource('payments', PaymentController::class);
Route::resource('report-cards', ReportCardController::class);