<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaymentHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PaymentsIndexResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = PaymentsIndexResource::collection(
            Payment::with('payer', 'payer.profilePhoto')->latest()->paginate(10)
        )->resource;

        return Inertia::render('Admin/Payments/Index', [
            'data' => json_decode(json_encode($payments)),
            'baseLink' => '/admin/payments/indexDataApi',
            'type' => 'payment',
        ]);
    }

    public function indexDataApi(Request $request){
        $searchText = $request->filters['searchText'] ?? '';
        $payments = PaymentsIndexResource::collection(
            Payment::with('payer', 'payer.profilePhoto')
                ->where('track_id', 'LIKE', "%$searchText%")
                ->latest()->paginate(10)
        )->resource;

        return $payments;
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        return $payment;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response(null, 200);
    }

    public function deleteMultiple(Request $request){
        // $request is an array of permission ids
        Payment::destroy($request->toArray());
        return response(null, 200);
    }
}
