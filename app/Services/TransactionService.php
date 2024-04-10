<?php

namespace App\Services;

use App\Models\Account;
use App\Enums\PaymentMethod;

class TransactionService
{
    /**
     * Create a transaction with the provided data.
     *
     * @param  array  $data The transaction data including 'forma_pagamento', 'conta_id', and 'valor'.
     * @return \App\Models\Account|null The updated account object, or null if the account was not found.
     * @throws \InvalidArgumentException If the transaction amount exceeds the account balance or if the account is not found.
     */
    public function createTransaction($data)
    {
        try {
            if (!$this->isAmountLessThanAccountBalance($data['conta_id'], $data['valor'])) {
                throw new \InvalidArgumentException('The transaction amount exceeds the account balance.');
            }
    
            $finalAmount = $this->getFinalAmountFeeByPaymentMethod($data['forma_pagamento'], $data['valor']);
    
            $account = Account::where('conta_id', $data['conta_id'])->first();
            if (!$account) {
                throw new \InvalidArgumentException('Account not found');
            }
    
            $account->saldo = $account->saldo - $finalAmount;
            $account->save();
    
            return $account;
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * Get the final transaction amount based on the payment method and the initial amount.
     *
     * @param  string  $paymentMethod The payment method ('P' for Pix, 'C' for Credit Card, 'D' for Debit Card).
     * @param  float   $amount        The initial transaction amount.
     * @return float   The final transaction amount after applying any fees.
     * @throws \InvalidArgumentException If the payment method is invalid.
     */
    private function getFinalAmountFeeByPaymentMethod($paymentMethod, $amount)
    {
        switch ($paymentMethod) {
            case PaymentMethod::PIX:
                return $amount;
            case PaymentMethod::CREDIT_CARD:
                return $amount - ($amount * 0.05);
            case PaymentMethod::DEBIT_CARD:
                return $amount - ($amount * 0.03);
            default:
                throw new \InvalidArgumentException("Invalid payment method: $paymentMethod");
        }
    }

    /**
     * Check if the transaction amount is less than or equal to the account balance.
     *
     * @param  int     $accountId The ID of the account.
     * @param  float   $amount    The transaction amount to be checked.
     * @return bool    True if the transaction amount is less than or equal to the account balance, false otherwise.
     * @throws \InvalidArgumentException If the account is not found.
     */
    private function isAmountLessThanAccountBalance($accountId, $amount)
    {
        $account = Account::where('conta_id', $accountId)->first();
        if (!$account) {
            throw new \InvalidArgumentException('Account not found');
        }

        return $amount <= $account->saldo;
    }

}