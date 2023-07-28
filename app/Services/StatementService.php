<?php
namespace App\Services;

use Illuminate\Support\Facades\Session;

class StatementService{

    public function getStatements(){
        $user = Session::get('user');
        $wallet = $user->wallet;
        
        return $wallet->transactions()->orderByDesc('created_at')->paginate(5);
    }

}