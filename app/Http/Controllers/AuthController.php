<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use App\Events\UserRegistered;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    public function index()
    {
        $user = Session::get('user');
        $wallet = $user->wallet;
        $balance = $wallet ? $wallet->balance : 0;

        return view('auth/home')->with([
            'userData' => $user,
            'balance' => $balance
        ]);
    }

    public function signup(SignupRequest $request)
    {
        $validatedData = $request->validated();
        $hashedPass = Hash::make($validatedData['password']);
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $hashedPass
        ]);
        $successMessage = 'Your account has been created!';

        event(new UserRegistered($user));

        return redirect('/')->with([
            'success' => $successMessage
        ]);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();


        if (!$user) {
            return Redirect::back()->with('errorEmail', 'Incorrect Email');
        }

        $isPassword = Hash::check($request->password, $user->password);
        if (!$isPassword) {
            return Redirect::back()->with('errorPass', 'Incorrect Password');
        }

        $successMessage = 'Welcome to ABC bank';
        Session::put('user', $user);

        return redirect('/home')->with([
            'success' => $successMessage
        ]);
    }
}
