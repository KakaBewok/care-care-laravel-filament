<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingPaymentController;
use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'index'])->name('front.index'); //home page

Route::get('/transactions', [FrontController::class, 'transactions'])->name('front.transactions');

Route::post('/transactions/details', [FrontController::class, 'transaction_details'])->name('front.transaction.details');

Route::get('/search', [FrontController::class, 'search'])->name('front.search'); //search store with specific service and city

Route::get('/store/details/{carStore:slug}', [FrontController::class, 'details'])->name('front.details'); //details of store you are looking for

Route::get('/booking/{carStore:slug}', [FrontController::class, 'booking'])->name('front.booking');

Route::post('/booking/{carStore:slug}/{carService:slug}', [BookingController::class, 'store'])->name('booking.store');

Route::get('/booking/{carStore}/{carService}/payment', [BookingPaymentController::class, 'show'])->name('booking-payment.show');

Route::post('/booking/payment', [BookingPaymentController::class, 'store'])->name('booking-payment.store');

Route::get('/booking/success/{bookingTransaction}', [FrontController::class, 'success_booking'])->name('front.success.booking');
