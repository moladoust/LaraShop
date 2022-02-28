<?php

namespace Database\Factories;

use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAddressFactory extends Factory
{
    protected $model = UserAddress::class;

    public function definition()
    {
        $addresses = [
            ["address 1", "address 2", "address 3"],
            ["address 4", "address 5", "address 6"],
            ["address 7", "address 8", "address 9"],
            ["address 10", "address 11", "address 12"],
            ["address 13", "address 14", "address 15"],
        ];
        $address   = $this->faker->randomElement($addresses);

        return [
            'province'      => $address[0],
            'city'          => $address[1],
            'district'      => $address[2],
            'address'       => sprintf('the first%dStreet No.%dNo', $this->faker->randomNumber(2), $this->faker->randomNumber(3)),
            'zip'           => $this->faker->postcode,
            'contact_name'  => $this->faker->name,
            'contact_phone' => $this->faker->phoneNumber,
        ];
    }
}
