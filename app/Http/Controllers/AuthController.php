<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use App\Events\UserRegistered;
use App\Http\Requests\SignupRequest;
use App\Models\Wallet;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    //
    public function index()
    {
        $user = Session::get('user');
        $wallet = Wallet::where('user_id', $user->id)->first();
        $balance = $wallet ? $wallet->balance : 0;
        return view('auth/home')->with([
            'userData' => $user,
            'balance' => $balance
        ]);
    }

    public function signup(SignupRequest $req)
    {
        $validatedData = $req->validated();
        $hashedPass = Hash::make($validatedData['password']);
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $hashedPass
        ]);
        $successMessage = 'Your account has been created!';
        event(new UserRegistered($user));
        return redirect('/')->with('success', $successMessage);
    }

    public function login(Request $req)
    {
        $user = User::where('email', $req->email)->first();
        try {
            if ($user) {
                $isPassword = Hash::check($req->password, $user->password);
                if ($isPassword) {
                    $successMessage = 'Welcome to ABC bank';
                    Session::put('user', $user);
                    return redirect('/home')->with('success', $successMessage);
                } else {
                    return Redirect::back()->with('errorPass', 'Incorrect Password');
                }
            } else {
                return Redirect::back()->with('errorEmail', 'Incorrect Email');
            }
        } catch (\Throwable $err) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
}
