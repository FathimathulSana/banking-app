<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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
    return view('auth/login');
});
Route::post('/login', [AuthController::class, 'login']);
Route::get('/signup', function () {
    return view('auth/signup');
});
Route::post('/signup', [AuthController::class, 'signup']);
Route::get('/home', [AuthController::class,'index']);

Route::get('/deposit', function () {
    return view('transactions/deposit');
});
Route::post('/deposit',[TransactionsController::class,'depositMoney']);
Route::get('/withdraw', function () {
    return view('transactions/withdraw');
});
Route::get('/transfer', function () {
    return view('transactions/transfer');
});
Route::get('/statement', function () {
    return view('transactions/statement');
});
Route::post('/logout',function(){
    Session::forget('user');
    return redirect('/');
});