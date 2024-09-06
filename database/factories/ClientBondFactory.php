<?php

namespace Database\Factories;

use App\Models\ClientBond;
use App\Models\Radius\Radgroupcheck;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faq>
 */
class ClientBondFactory extends Factory
{
    protected $model = ClientBond::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => ucfirst(fake()->word()),
            'radgroupcheck_id' => Radgroupcheck::all()->random()->id,
        ];
    }
}
