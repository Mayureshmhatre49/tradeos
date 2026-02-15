<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/transactions', function () {
    return view('transactions');
})->name('transactions');

Route::get('/transactions/{uuid}', function ($uuid) {
    return view('transaction-detail', ['uuid' => $uuid]);
})->name('transaction.detail');


Route::get('/lcs', function () {
    return view('lcs');
})->name('lcs');

Route::get('/lcs/{uuid}', function ($uuid) {
    return view('lc-detail', ['uuid' => $uuid]);
})->name('lc.detail');

Route::get('/shipments', function () {
    return view('shipments');
})->name('shipments');

Route::get('/shipments/{uuid}', function ($uuid) {
    return view('shipment-detail', ['uuid' => $uuid]);
})->name('shipment.detail');

Route::get('/counterparties', function () {
    return view('counterparties');
})->name('counterparties');

Route::get('/counterparties/{uuid}', function ($uuid) {
    return view('counterparty-detail', ['uuid' => $uuid]);
})->name('counterparty.detail');

Route::get('/compliance', function () {
    return view('compliance');
})->name('compliance');

