<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AccountService;
use Illuminate\Support\Facades\Validator;


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

        $validator = Validator::make($request->all(), [
            'conta_id' => 'required|integer|unique:accounts',
            'valor' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $conta_id = $request['conta_id'];
        $valor = $request['valor'];

        $account = $this->accountService->createAccountWithBalance($conta_id, $valor);

        $response = [
            'conta_id' => $account->conta_id,
            'saldo' => $account->saldo
        ];

        return response()->json($response, 201);
    }

    /**
     * Display the details of an account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAccount(Request $request)
    {
        $accountId = $request->query('id');

        $account = $this->accountService->findAccount($accountId);

        if (!$account) {
            return response()->json(['errors' => ['Account not found']], 404);
        }

        return response()->json($account);
    }
}
