<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingPaymentRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Models\BookingTransaction;
use App\Models\CarService;
use App\Models\CarStore;
use App\Models\City;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Log;
use session;

class FrontController extends Controller
{
    public function index()
    {
        $cities = City::all();
        $services = CarService::all();
        return view('front.index', compact('cities', 'services'));
    }

    public function search(Request $request)
    {
        $cityId = $request->query('city_id');
        $serviceTypeId = $request->query('service_type');

        //QUERY TO DATABASE:

        //get service
        $carService = CarService::where('id', $serviceTypeId)->first();
        if (!$carService) {
            return redirect()->back()->with('error', 'Service type not found');
        }

        //get store with service and city
        $stores = CarStore::whereHas('storeServices', function (Builder $query) use ($carService) {
            $query->where('car_service_id', $carService->id);
        })->where('city_id', $cityId)->get();

        //get city
        $city = City::find($cityId);

        session()->put('serviceTypeId', $serviceTypeId);

        return view('front.stores', [
            'stores' => $stores,
            'carService' => $carService,
            'cityName' => $city ? $city->name : 'Unknown city'
        ]);
    }

    public function details(CarStore $carStore)
    {
        $serviceTypeId = session()->get('serviceTypeId');
        $carService = CarService::where('id', $serviceTypeId)->first();
        return view('front.details', compact('carStore', 'carService'));
    }

    public function booking(CarStore $carStore)
    {
        session()->put('carStoreId', $carStore->id);

        $serviceTypeId = session()->get('serviceTypeId');
        $service = CarService::where('id', $serviceTypeId)->first();

        return view('front.booking', compact('carStore', 'service'));
    }

    public function booking_store(StoreBookingRequest $request)
    {
        $validated = $request->validated();
        $csName = $request->input('name');
        $csPhoneNumber = $request->input('phone_number');
        $csTimeAt = $request->input('time_at');

        session()->put('csName', $csName);
        session()->put('csPhoneNumber', $csPhoneNumber);
        session()->put('csTimeAt', $csTimeAt);

        $serviceTypeId = session()->get('serviceTypeId');
        $carStoreId = session()->get('carStoreId');

        return redirect()->route('front.booking.payment', [$carStoreId, $serviceTypeId]);
    }

    public function booking_payment(CarStore $carStore, CarService $carService)
    {
        $ppn = 0.11;
        $totalPpn = $carService->price * $ppn;
        $bookingFee = 25000;
        $grandTotal = $totalPpn + $bookingFee + $carService->price;

        session()->put('totalAmount', $grandTotal);
        // dd(session()->all());

        return view('front.payment', compact('carService', 'carStore', 'totalPpn', 'bookingFee', 'grandTotal'));
    }

    // public function booking_payment_store(StoreBookingPaymentRequest $request)
    // {

    //     $csName = session()->get('csName');
    //     $csPhoneNumber = session()->get('csPhoneNumber');
    //     $csTimeAt = session()->get('csTimeAt');

    //     $totalAmount = session()->get('totalAmount');

    //     $serviceTypeId = session()->get('serviceTypeId');
    //     $carStoreId = session()->get('carStoreId');

    //     $bookingTransactionId = null;

    //     DB::transaction(function () use ($request, $csName, $csPhoneNumber, $csTimeAt, $totalAmount, $serviceTypeId, $carStoreId, &$bookingTransactionId) {
    //         $validated = $request->validated();

    //         if ($request->hasFile('proof')) {
    //             $proofPath = $request->file('proof')->store('proofs', 'public');
    //             $validated['proof'] =  $proofPath;
    //         }

    //         $validated['name'] = $csName;
    //         $validated['total_amount'] = $totalAmount;
    //         $validated['phone_number'] = $csPhoneNumber;
    //         $validated['started_at'] = Carbon::tomorrow()->format('Y-m-d');
    //         $validated['time_at'] = $csTimeAt;
    //         $validated['car_service_id'] = $serviceTypeId;
    //         $validated['car_store_id'] = $carStoreId;
    //         $validated['is_paid'] = false;
    //         $validated['trx_id'] = BookingTransaction::generateUniqueTrxId();

    //         //save booking data
    //         $newBooking = BookingTransaction::create($validated);

    //         $bookingTransactionId = $newBooking->id;
    //     });

    //     return redirect()->route('front.success.booking', $bookingTransactionId);
    // }

    public function success_booking(BookingTransaction $booking)
    {
        return view('welcome', compact('booking'));
    }
}
