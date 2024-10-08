<?php

namespace Database\Factories;

use App\Enums\AddressType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => AddressType::Billing,
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->email(),
            'address1' => fake()->address(),
            'address2' => '',
            'zipcode' => fake()->postcode(),
            'city' => fake()->city(),
            'province' => 'CABA',
            'country' => 'Argentina',
        ];
    }
}
