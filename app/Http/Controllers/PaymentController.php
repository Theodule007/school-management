<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Http\Request;
use PaymentService;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Payment::latest()->paginate();
    }

    public function create()
    {
        return view('payments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        Request $request,
        PaymentService $service
    ) {
        $enrollment = Enrollment::findOrFail($request->enrollment_id);

        $service->store($enrollment, $request->all());

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        return $payment;
    }

    public function edit(Payment $payment)
    {
        return view('payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $payment->update($request->all());

        return back();
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return back();
    }
}
