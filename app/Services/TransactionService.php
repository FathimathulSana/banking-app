<?php

namespace App\Services;

use App\Events\TransactionEvent;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class TransactionService
{
    public function depositMoney(Wallet $wallet, $amount)
    {
        $wallet->balance += $amount;
        $wallet->save();
        event(new TransactionEvent($wallet, $amount, 'Credit', 'Deposit', $wallet->balance));
    }

    public function withdrawMoney(Wallet $wallet, $amount)
    {
        if ($wallet->balance < $amount) {
            return Redirect::back()->with('error', 'Insufficient balance for transfer.');
        }
        $wallet->balance -= $amount;
        $wallet->save();
        event(new TransactionEvent($wallet, $amount, 'Debit', 'Withdraw', $wallet->balance));
    }

    public function transferMoney(Wallet $senderWallet, Wallet $receiverWallet, $amount)
    {
        if ($senderWallet->balance < $amount) {
            return Redirect::back()->with('error', 'Insufficient balance for transfer.');
        }
        DB::beginTransaction();

        $senderWallet->balance -= $amount;
        $senderWallet->save();
        event(new TransactionEvent($senderWallet, $amount, 'Debit', 'Transfer ro  ' . $receiverWallet->user->email, $senderWallet->balance));

        $receiverWallet->balance += $amount;
        $receiverWallet->save();
        event(new TransactionEvent($receiverWallet, $amount, 'Credit', 'Transfer from ' . $senderWallet->user->email, $receiverWallet->balance));

        DB::commit();
    }
}
