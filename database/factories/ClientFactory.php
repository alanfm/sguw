<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\ClientBond;
use App\Models\Radius\Radcheck;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faq>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'registry' => fake()->unique()->numberBetween(10000, 99999999999),
            'cpf' => str_pad(fake()->unique()->numberBetween(100000, 99999999999), 11, '0', STR_PAD_LEFT),
            'birth' => fake()->date(),
            'radcheck_id' => Radcheck::all()->random()->id,
            'client_bond_id' => ClientBond::all()->random()->id,
        ];
    }
}
