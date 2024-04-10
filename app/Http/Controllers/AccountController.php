<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\AccountService;

class AccountController extends Controller
{
    protected $accountService;


    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Create a new account with balance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAccountWithBalance(Request $request)
    {
        $validatedData = $request->validate([
            'conta_id' => 'required|integer',
            'valor' => 'required|numeric',
        ]); //@TODO add a new interface for this validation

        $conta_id = $validatedData['conta_id'];
        $valor = $validatedData['valor'];

        $account = $this->accountService->createAccountWithBalance($conta_id, $valor);

        $response = [
            'conta_id' => $account->conta_id,
            'saldo' => $account->saldo
        ];

        return response()->json($response, 201);
    }
}
