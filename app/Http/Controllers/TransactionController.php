<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Create a transaction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTransaction(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'forma_pagamento' => 'required|string',
                'conta_id' => 'required|integer',
                'valor' => 'required|numeric',
            ]);

            $response = $this->transactionService->createTransaction($validatedData);

            return response()->json($response);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['errors' => $e->getMessage()], $e->getCode());
        }
    }
}
