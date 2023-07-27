<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Http\Requests\TransferRequest;
use App\Models\User;
use App\Services\StatementService;
use App\Services\TransactionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class TransactionsController extends Controller
{
    protected $transactionService;
    protected $statementService;
    public function __construct(TransactionService $transactionService,StatementService $statementService)
    {
        $this->transactionService = $transactionService;
        $this->statementService =$statementService;
    }
    //
    public function depositMoney(TransactionRequest $req)
    {
        $validatedData = $req->validated();
        $user = Session::get('user');
        $wallet = $user->wallet;
        if ($wallet) {
            $this->transactionService->depositMoney($wallet, $validatedData['amount']);
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
            $wallet = $user->wallet;
            $this->transactionService->withdrawMoney($wallet, $validatedData['amount']);
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
        try {
            $senderWallet = $sender->wallet;
            $receiverWallet = $receiver->wallet;

            $this->transactionService->transferMoney($senderWallet, $receiverWallet, $validatedData['amount']);
            return redirect('/home')->with('success', 'Amount Transferred successfully');
        } catch (\Throwable $err) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Transfer failed. Please try again later.');
        }
    }
    public function getStatements()
    {
        $statements = $this->statementService->getStatements();
        return view('transactions/statements',compact('statements'));
    }
}
