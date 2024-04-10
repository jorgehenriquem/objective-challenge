<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class AccountController extends Controller
{
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
        ]);

        $conta_id = $validatedData['conta_id'];
        $valor = $validatedData['valor'];

        $response = [
            'conta_id' => $conta_id,
            'saldo' => $valor
        ];

        return response()->json($response, 201);
    }
}
