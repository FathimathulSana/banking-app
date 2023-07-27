<?php

namespace App\Http\Controllers;

use App\Events\TransactionEvent;
use App\Http\Requests\TransactionRequest;
use App\Http\Requests\TransferRequest;
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
    public function depositMoney(TransactionRequest $req)
    {
        $validatedData = $req->validated();
        $user = Session::get('user');
        $wallet = $user->wallet;
        if ($wallet) {
            $wallet->balance += $validatedData['amount'];
            $wallet->save();
            event(new TransactionEvent($wallet, $validatedData['amount'], 'Credit', 'Deposit', $wallet->balance));
            return redirect('/home')->with('success', 'Amount deposited successfully');
        } else {
            return Redirect::back()->with('error', 'Account not found!');
        }
    }
    public function withdrawMoney(TransactionRequest $req)
    {
        $validatedData = $req->validated();
        $user = Session::get('user');
        if ($user) {
            $wallet=$user->wallet;
            if ($wallet->balance < $validatedData['amount']) {
                return Redirect::back()->with('error', 'Insufficient balance for transfer.');
            }
            $wallet->balance -= $validatedData['amount'];
            $wallet->save();
            event(new TransactionEvent($wallet, $validatedData['amount'], 'Debit', 'Withdraw', $wallet->balance));
            return redirect('/home')->with('success', 'Amount Withdrawed successfully');
        } else {
            return Redirect::back()->with('error', 'Account not found!');
        }
    }
    public function transferMoney(TransferRequest $req)
    {
        $validatedData = $req->validated();
        $sender = Session::get('user');
        $receiver = User::where('email', $validatedData['receiver_email'])->first();
        
        DB::beginTransaction();
        try {
            $senderWallet = $sender->wallet;
            $receiverWallet = $receiver->wallet;
            if ($senderWallet->balance < $validatedData['amount']) {
                return Redirect::back()->with('error', 'Insufficient balance for transfer.');
            }
            $senderWallet->balance -= $validatedData['amount'];
            $senderWallet->save();
            event(new TransactionEvent($senderWallet, $validatedData['amount'], 'Debit', 'Transfer ro  ' . $receiver->email, $senderWallet->balance));

            $receiverWallet->balance += $validatedData['amount'];
            $receiverWallet->save();
            event(new TransactionEvent($receiverWallet, $validatedData['amount'], 'Credit', 'Transfer from ' . $sender->email, $receiverWallet->balance));

            DB::commit();
            return redirect('/home')->with('success', 'Amount Transferred successfully');
        } catch (\Throwable $err) {
            //throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', 'Transfer failed. Please try again later.');
        }
    }
    public function getStatement()
    {
        $user = Session::get('user');
        $userWallet = Wallet::where('user_id', $user->id)->first();
        $accountStatements = Transaction::where('wallet_id', $userWallet->id)->paginate(5);
        return view('transactions/statement')->with([
            'statements' => $accountStatements,
        ]);
    }
}
