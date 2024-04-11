<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Faker\Factory as Faker;
use App\Models\Account;
use App\Enums\PaymentMethod;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_pix_transaction_a_successful_response(): void
    {
        $account = Account::factory()->create();
        $faker = Faker::create();
        $transactionValue = $faker->randomFloat(2, 0, $account->saldo - 1);
        $data = [
            'forma_pagamento' => PaymentMethod::PIX,
            'conta_id' => $account->conta_id,
            'valor' => $transactionValue
        ];
        $newBalanceValidation = number_format($account->saldo - $transactionValue, 2);

        $response = $this->postJson('/api/transacao', $data);

        $response->assertStatus(200)
            ->assertJson([
                'conta_id' => $data['conta_id'],
                'saldo' => $newBalanceValidation
            ]);

        $this->assertDatabaseHas('accounts', [
            'conta_id' => $data['conta_id'],
            'saldo' => $newBalanceValidation
        ]);
    }

    public function test_credit_card_transaction_a_successful_response(): void
    {
        $account = Account::factory()->create();
        $faker = Faker::create();
        $transactionValue = $faker->randomFloat(2, 0, $account->saldo - 1);
        $data = [
            'forma_pagamento' => PaymentMethod::CREDIT_CARD,
            'conta_id' => $account->conta_id,
            'valor' => $transactionValue
        ];
        $finalAmount = $transactionValue - ($transactionValue * 0.05);
        $newBalanceValidation = number_format($account->saldo - $finalAmount, 2);

        $response = $this->postJson('/api/transacao', $data);

        $response->assertStatus(200)
            ->assertJson([
                'conta_id' => $data['conta_id'],
                'saldo' => $newBalanceValidation
            ]);

        $this->assertDatabaseHas('accounts', [
            'conta_id' => $data['conta_id'],
            'saldo' => $newBalanceValidation
        ]);
    }

    public function test_debit_card_transaction_a_successful_response(): void
    {
        $account = Account::factory()->create();
        $faker = Faker::create();
        $transactionValue = $faker->randomFloat(2, 0, $account->saldo - 1);
        $data = [
            'forma_pagamento' => PaymentMethod::DEBIT_CARD,
            'conta_id' => $account->conta_id,
            'valor' => $transactionValue
        ];
        $finalAmount = $transactionValue - ($transactionValue * 0.03);
        $newBalanceValidation = number_format($account->saldo - $finalAmount, 2);

        $response = $this->postJson('/api/transacao', $data);

        $response->assertStatus(200)
            ->assertJson([
                'conta_id' => $data['conta_id'],
                'saldo' => $newBalanceValidation
            ]);

        $this->assertDatabaseHas('accounts', [
            'conta_id' => $data['conta_id'],
            'saldo' => $newBalanceValidation
        ]);
    }

    public function test_transaction_a_inexisting_account_with_a_error_response(): void
    {
        $faker = Faker::create();
        $randomPaymentMethod = $faker->randomElement([
            PaymentMethod::PIX,
            PaymentMethod::CREDIT_CARD,
            PaymentMethod::DEBIT_CARD,
        ]);
        $transactionValue = $faker->randomFloat(2, 0);
        $data = [
            'forma_pagamento' => $randomPaymentMethod,
            'conta_id' => $faker->randomNumber(5),
            'valor' => $transactionValue
        ];

        $response = $this->postJson('/api/transacao', $data);

        $response->assertStatus(404)
            ->assertJsonFragment(['errors' => 'Account not found']);
    }

    public function test_transaction_a_insuficient_amount_with_a_error_response(): void
    {
        $account = Account::factory()->create();
        $faker = Faker::create();
        $randomPaymentMethod = $faker->randomElement([
            PaymentMethod::PIX,
            PaymentMethod::CREDIT_CARD,
            PaymentMethod::DEBIT_CARD,
        ]);
        $insuficientTransactionValue = $faker->randomFloat(2, $account->saldo + 0.01, $account->saldo + 1000);
        $data = [
            'forma_pagamento' => $randomPaymentMethod,
            'conta_id' => $account->conta_id,
            'valor' => $insuficientTransactionValue
        ];

        $response = $this->postJson('/api/transacao', $data);

        $response->assertStatus(422)
            ->assertJsonFragment(['errors' => 'The transaction amount exceeds the account balance.']);
    }

    public function test_transaction_a_invalid_payment_method_with_a_error_response(): void
    {
        $account = Account::factory()->create();
        $faker = Faker::create();
        $randomWord = $faker->regexify('[A-Za-z]{2,3}');
        $randomPaymentMethod = $randomWord;
        $data = [
            'forma_pagamento' => $randomPaymentMethod,
            'conta_id' => $account->conta_id,
            'valor' => $account->saldo
        ];

        $response = $this->postJson('/api/transacao', $data);

        $response->assertStatus(422)
            ->assertJsonFragment(['errors' => 'Invalid payment method: ' . $randomWord]);
    }

}