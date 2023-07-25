<?php

use App\Http\Controllers\AuthController;
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
    return view('auth/login');
});
Route::post('/login', [AuthController::class, 'login'])->middleware('web');;
Route::get('/signup', function () {
    return view('auth/signup');
});
Route::post('/signup', [AuthController::class, 'signup']);
Route::get('/home', function () {
    return view('auth/home');
});
Route::get('/deposit', function () {
    return view('transactions/deposit');
});
Route::get('/withdraw', function () {
    return view('transactions/withdraw');
});
Route::get('/transfer', function () {
    return view('transactions/transfer');
});
Route::get('/statement', function () {
    return view('transactions/statement');
});

