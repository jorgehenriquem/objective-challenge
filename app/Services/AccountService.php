<?php

namespace App\Services;

use App\Models\Account;

class AccountService
{
    public function createAccountWithBalance($conta_id, $valor)
    {

        $account = new Account();
        $account->conta_id = $conta_id;
        $account->saldo = $valor;
        $account->save();

        return $account;
    }

    public function findAccount($id)
    {
        return Account::find($id);
    }
    
}