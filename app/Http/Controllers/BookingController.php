<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreBookingRequest;

class BookingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request)
    {
        $csName = $request->input('name');
        $csPhoneNumber = $request->input('phone_number');
        $csTimeAt = $request->input('time_at');

        session()->put('csName', $csName);
        session()->put('csPhoneNumber', $csPhoneNumber);
        session()->put('csTimeAt', $csTimeAt);

        $serviceTypeId = session()->get('serviceTypeId');
        $carStoreId = session()->get('carStoreId');

        return redirect()->route('booking-payment.show', [$carStoreId, $serviceTypeId]);
    }
}
