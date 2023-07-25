<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    //
    public function signup(Request $req)
    {
        $req->validate([
            'name' => 'min:3|max:10|regex:/^[A-Za-z]+$/',
            'email' => 'regex:/^[a-z0-9]+@[a-z]+\.[a-z]{2,3}$/|unique:users',
            'password' => 'min:5'
        ]);
        $hashedPass = Hash::make($req->input('password'));
        User::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => $hashedPass
        ]);
        $successMessage='Your account has been created!';
        return redirect('/')->with('success',$successMessage);
    }

    public function login(Request $req)
    {
        $data = $req->only('email', 'password');
        $user = User::where('email', $data['email'])->first();
        try {
            if ($user) {
                $isPassword = Hash::check($data['password'], $user->password);
                if ($isPassword) {
                    $successMessage='Welcome to ABC bank';
                    return redirect('/home')->with('success',$successMessage)->with('userData',$user);
                } else {
                    return Redirect::back()->with('errorPass','Incorrect Password');
                }
            } else {
                return Redirect::back()->with('errorEmail','Incorrect Email');
            }
        } catch (\Throwable $err) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

   
}
