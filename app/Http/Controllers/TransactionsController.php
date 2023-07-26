<?php

namespace App\Http\Controllers;

use App\Events\TransactionEvent;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class TransactionsController extends Controller
{
    //
    public function depositMoney(Request $req){
        $req->validate([
            'amount'=>'required|numeric|min:0.01'
        ]);
        $userData=Session::get('user');
        if($userData){
            $wallet=Wallet::where('user_id',$userData->id)->first();
            if(!$wallet){
                return response()->json(['error'=>'Wallet not found'],404);
            }
            $wallet->balance+=$req->amount;
            $wallet->save();
         event(new TransactionEvent($wallet,$req->amount,'Credit','Deposit'));
       return redirect('/home')->with('success','Amount deposited successfully');
        }else{
            return Redirect::back()->with('error','Account not found!');
        }
    }
  
}
