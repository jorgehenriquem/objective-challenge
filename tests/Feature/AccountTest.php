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

        $response->assertStatus(422)
        ->assertJsonStructure(['errors' => ['conta_id']])
        ->assertJsonFragment([
            'errors' => [
                'conta_id' => [
                    'The conta id has already been taken.'
                ]
            ]
        ]);

    }

    public function test_create_account_with_a_negative_balance_error_response(): void
    {
        $faker = Faker::create();
        $data = [
            'conta_id' => $faker->randomNumber(5),
            'valor' =>  $faker->randomFloat(2, -1000, 1000)
        ];
        $response = $this->postJson('/api/conta', $data);

        $response->assertStatus(422)
        ->assertJsonStructure(['errors' => ['valor']])
        ->assertJsonFragment([
            'errors' => [
                'valor' => [
                    'The valor field must be at least 0.'
                ]
            ]
        ]);

    }

    public function test_visualize_account_with_a_successful_response(): void
    {
        $account = Account::factory()->create();

        $response = $this->get('/api/conta/?id=' . $account->conta_id);
        
        $response->assertStatus(200)
        ->assertJson([
            'conta_id' => $account->conta_id,
            'saldo' => $account->saldo
        ]);


    }

}