<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\CarStore;
use App\Models\CarService;
use Illuminate\Http\Request;
use App\Models\BookingTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreBookingPaymentRequest;

class BookingPaymentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingPaymentRequest $request)
    {
        $csName = session()->get('csName');
        $csPhoneNumber = session()->get('csPhoneNumber');
        $csTimeAt = session()->get('csTimeAt');

        $totalAmount = session()->get('totalAmount');

        $serviceTypeId = session()->get('serviceTypeId');
        $carStoreId = session()->get('carStoreId');

        $bookingTransactionId = null;

        DB::transaction(function () use ($request, $csName, $csPhoneNumber, $csTimeAt, $totalAmount, $serviceTypeId, $carStoreId, &$bookingTransactionId) {
            $validated = $request->validated();

            if ($request->hasFile('proof')) {
                $proofPath = $request->file('proof')->store('proofs', 'public');
                $validated['proof'] =  $proofPath;
            }

            $validated['name'] = $csName;
            $validated['total_amount'] = $totalAmount;
            $validated['phone_number'] = $csPhoneNumber;
            $validated['started_at'] = Carbon::tomorrow()->format('Y-m-d');
            $validated['time_at'] = $csTimeAt;
            $validated['car_service_id'] = $serviceTypeId;
            $validated['car_store_id'] = $carStoreId;
            $validated['is_paid'] = false;
            $validated['trx_id'] = BookingTransaction::generateUniqueTrxId();

            //save booking data
            $newBooking = BookingTransaction::create($validated);

            $bookingTransactionId = $newBooking->id;
        });
        return redirect()->route('front.success.booking', $bookingTransactionId);
    }

    /**
     * Display the specified resource.
     */
    public function show(CarStore $carStore, CarService $carService)
    {
        $ppn = 0.11;
        $totalPpn = $carService->price * $ppn;
        $bookingFee = 25000;
        $grandTotal = $totalPpn + $bookingFee + $carService->price;

        session()->put('totalAmount', $grandTotal);
        session()->reflash();
        session()->all();
        return view('front.payment', compact('carService', 'carStore', 'totalPpn', 'bookingFee', 'grandTotal'));
    }
}
