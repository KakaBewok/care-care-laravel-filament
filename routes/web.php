<?php

use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'index'])->name('front.index'); //home page

Route::get('/transactions', [FrontController::class, 'transactions'])->name('front.transactions');

Route::post('/transactions/details', [FrontController::class, 'transaction_details'])->name('front.transaction.details');

Route::get('/search', [FrontController::class, 'search'])->name('front.search'); //search store with specific service and city

Route::get('/store/details/{carStore:slug}', [FrontController::class, 'details'])->name('front.details'); //details of store you are looking for

Route::get('/booking/{CarStore:slug}', [FrontController::class, 'booking'])->name('front.booking');

Route::post('/booking/{CarStore:slug}/{CarService:slug}', [FrontController::class, 'booking_store'])->name('front.booking.store');

Route::get('/booking/{CarStore}/{CarService}/payment', [FrontController::class, 'booking_payment'])->name('front.booking.payment');

Route::post('/booking/payment/submit', [FrontController::class, 'booking_payment_store'])->name('front.booking.payment.store');

Route::get('/booking/success/{BookingTransaction}', [FrontController::class, 'success_booking'])->name('front.success.booking');
