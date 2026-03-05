<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord principal.
     */
    public function index()
    {
        return view('dashboard.index');
    }
}