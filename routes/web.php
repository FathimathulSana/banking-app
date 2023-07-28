<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;


Route::get('/', function () {
    return view('auth/login');
});
Route::post('/login', [AuthController::class, 'login']);
Route::get('/signup', function () {
    return view('auth/signup');
});
Route::post('/signup', [AuthController::class, 'signup']);
Route::get('/home', [AuthController::class, 'index']);

Route::get('/deposit', function () {
    return view('transactions/deposit');
});
Route::post('/deposit', [TransactionsController::class, 'depositMoney']);
Route::get('/withdraw', function () {
    return view('transactions/withdraw');
});
Route::post('/withdraw', [TransactionsController::class, 'withdrawMoney']);
Route::get('/transfer', function () {
    return view('transactions/transfer');
});
Route::post('/transfer', [TransactionsController::class, 'transferMoney']);
Route::get('/statement', [TransactionsController::class, 'getStatements']);

Route::post('/logout', function () {
    Session::forget('user');
    return redirect('/');
});
