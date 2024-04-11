<?php

namespace App\Services;

use App\Models\Account;

class AccountService
{
    /**
     * Creates a new account with the provided account ID and balance.
     *
     * @param int $conta_id The ID of the account.
     * @param float $valor The initial balance of the account.
     * @return \App\Models\Account The newly created account.
     */
    public function createAccountWithBalance($conta_id, $valor)
    {

        $account = new Account();
        $account->conta_id = $conta_id;
        $account->saldo = $valor;
        $account->save();

        return $account;
    }
    /**
     * Finds and returns an account by its ID.
     * 
     * @param int $id The ID of the account to find.
     * @return \App\Models\Account|null The found account, or null if not found.
     */
    public function findAccount($id)
    {
        return Account::find($id);
    }

}