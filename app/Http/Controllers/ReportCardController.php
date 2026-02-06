<?php

namespace App\Http\Controllers;

use App\Models\ReportCard;
use Illuminate\Http\Request;
use ReportCardService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;


class ReportCardController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ReportCard::paginate();
    }

    public function create()
    {
        return view('report-cards.create');
    }

    public function store(Request $request)
    {
        ReportCard::create($request->all());

        return back();
    }

    public function show(ReportCard $reportCard)
    {
        return $reportCard;
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReportCard $reportCard)
    {
        return view('report-cards.edit', compact('reportCard'));
    }

    public function update(Request $request, ReportCard $reportCard)
    {
        $reportCard->update($request->all());

        return back();
    }

    public function destroy(ReportCard $reportCard)
    {
        $reportCard->delete();

        return back();
    }

    public function validate(
        ReportCard $reportCard,
        ReportCardService $service
    ) {
        $this->authorize('validate', $reportCard);

        $service->validate($reportCard, Auth::user());

        return back();
    }
}
