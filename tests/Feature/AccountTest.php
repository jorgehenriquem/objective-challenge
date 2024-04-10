<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Faker\Factory as Faker;
use App\Models\Account;
use Database\Factories\AccountFactory;


class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_account_a_successful_response(): void
    {
        $faker = Faker::create();
        $data = [
            'conta_id' => $faker->randomNumber(5),
            'valor' => $faker->randomFloat(2, 0, 1000)
        ];
        $response = $this->postJson('/api/conta', $data);

        $response->assertStatus(201)
        ->assertJson([
            'conta_id' => $data['conta_id'],
            'saldo' => $data['valor']
        ]);

        $this->assertDatabaseHas('accounts', [
            'conta_id' => $data['conta_id'],
            'saldo' => $data['valor']
        ]);
    }

    public function test_create_existing_account_a_error_response(): void
    {
        $account = Account::factory()->create();

        $data = [
            'conta_id' => $account->conta_id,
            'valor' =>  $account->saldo
        ];
        $response = $this->postJson('/api/conta', $data);

        $response->assertStatus(422);

    }

}