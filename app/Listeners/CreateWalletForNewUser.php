<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Models\Wallet;


class CreateWalletForNewUser
{

    public function handle(UserRegistered $event)
    {
        //
        $user=$event->user;
        $wallet=new Wallet();
        $wallet->user_id=$user->id;
        $wallet->save();

    }
}
