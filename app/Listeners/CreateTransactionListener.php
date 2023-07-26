<?php

namespace App\Listeners;

use App\Events\TransactionEvent;
use App\Models\Transaction;

class CreateTransactionListener
{
 
    public function handle(TransactionEvent $event)
    {
        //
        $wallet=$event->wallet;
        $transaction=new Transaction();
        $transaction->wallet_id=$wallet->id;
        $transaction->amount=$event->amount;
        $transaction->transaction_type=$event->type;
        $transaction->details=$event->details;
        $transaction->balance=$event->balance;
        $transaction->save();
    }
}
