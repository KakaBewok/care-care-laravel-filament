<?php

namespace App\Http\Controllers;

use App\Models\BookingTransaction;
use App\Models\CarService;
use App\Models\CarStore;
use App\Models\City;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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

    public function success_booking(BookingTransaction $booking)
    {
        return view('front.success', compact('booking'));
    }
}
