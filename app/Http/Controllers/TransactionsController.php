<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Http\Requests\TransferRequest;
use App\Models\User;
use App\Services\StatementService;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class TransactionsController extends Controller
{
    protected $transactionService;
    protected $statementService;

    public function __construct(TransactionService $transactionService, StatementService $statementService)
    {
        $this->transactionService = $transactionService;
        $this->statementService = $statementService;
    }

    public function depositMoney(TransactionRequest $request)
    {
        $validatedData = $request->validated();
        $user = Session::get('user');
        $wallet = $user->wallet;

        if (!$wallet) {
            return Redirect::back()->with([
                'error' => 'Account not found!'
            ]);
        }

        $this->transactionService->depositMoney($wallet, $validatedData['amount']);

        return redirect('/home')->with([
            'success' => 'Amount deposited successfully'
        ]);
    }

    public function withdrawMoney(TransactionRequest $request)
    {
        $validatedData = $request->validated();
        $user = Session::get('user');


        if (!$user) {
            return Redirect::back()->with([
                'error' => 'Account not found!'
            ]);
        }

        $wallet = $user->wallet;
        $result = $this->transactionService->withdrawMoney($wallet, $validatedData['amount']);

        return $result;
    }

    public function transferMoney(TransferRequest $request)
    {
        $validatedData = $request->validated();
        $sender = Session::get('user');
        $receiver = User::where('email', $validatedData['receiver_email'])->first();
        $senderWallet = $sender->wallet;
        $receiverWallet = $receiver->wallet;

        $result = $this->transactionService->transferMoney($senderWallet, $receiverWallet, $validatedData['amount']);

        return $result;
    }

    public function getStatements()
    {
        $statements = $this->statementService->getStatements();

        return view('transactions/statement')->with([
            'statements' => $statements,
        ]);
    }
}
