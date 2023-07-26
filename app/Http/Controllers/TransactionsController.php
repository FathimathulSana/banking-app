<?php

namespace App\Http\Controllers;

use App\Events\TransactionEvent;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class TransactionsController extends Controller
{
    //
    public function depositMoney(Request $req)
    {
        $req->validate([
            'amount' => 'required|numeric|min:0.01'
        ]);
        $userData = Session::get('user');
        if ($userData) {
            $wallet = Wallet::where('user_id', $userData->id)->first();
            if (!$wallet) {
                return response()->json(['error' => 'Wallet not found'], 404);
            }
            $wallet->balance += $req->amount;
            $wallet->save();
            event(new TransactionEvent($wallet, $req->amount, 'Credit', 'Deposit',$wallet->balance));
            return redirect('/home')->with('success', 'Amount deposited successfully');
        } else {
            return Redirect::back()->with('error', 'Account not found!');
        }
    }
    public function withdrawMoney(Request $req)
    {
        $req->validate([
            'amount' => 'required|numeric|min:0.01'
        ]);
        $userData = Session::get('user');
        if ($userData) {
            $wallet = Wallet::where('user_id', $userData->id)->first();
            if (!$wallet) {
                return response()->json(['error' => 'Wallet not found'], 404);
            }
            $wallet->balance -= $req->amount;
            $wallet->save();
            event(new TransactionEvent($wallet, $req->amount, 'Debit', 'Withdraw',$wallet->balance));
            return redirect('/home')->with('success', 'Amount Withdrawed successfully');
        } else {
            return Redirect::back()->with('error', 'Account not found!');
        }
    }
    public function transferMoney(Request $req)
    {
        $req->validate([
            'receiver_email' => 'required|email|exists:users,email',
            'amount' => 'required|numeric|min:0.01'
        ]);
        $sender = Session::get('user');
        $senderWallet = Wallet::where('user_id', $sender->id)->first();
        if ($senderWallet->balance < $req->amount) {
            return Redirect::back()->with('error', 'Insufficient balance for transfer.');
        }
        $receiver = User::where('email', $req->receiver_email)->first();
        if ($receiver) {
            $receiverWallet = Wallet::where('user_id', $receiver->id)->first();
        }
        DB::beginTransaction();
        try {
            $senderWallet->balance -= $req->amount;
            $senderWallet->save();
            event(new TransactionEvent($senderWallet, $req->amount, 'Debit', 'Transfer ro  '.$receiver->email,$senderWallet->balance));

            $receiverWallet->balance += $req->amount;
            $receiverWallet->save();
            event(new TransactionEvent($receiverWallet, $req->amount, 'Credit', 'Transfer from '.$sender->email,$receiverWallet->balance));

            DB::commit();
            return redirect('/home')->with('success', 'Amount Transferred successfully');
        } catch (\Throwable $err) {
            //throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', 'Transfer failed. Please try again later.');
        }
    }
    public function getStatement(){
         $user=Session::get('user');
         $userWallet=Wallet::where('user_id',$user->id)->first();
         $accountStatements=Transaction::where('wallet_id',$userWallet->id)->paginate(5);
         return view('transactions/statement')->with([
            'statements'=>$accountStatements,
        ]);
    }
}
