<?php

namespace Database\Factories;

use App\Models\CouponCode;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponCodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CouponCode::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */

    public function definition()
    {
        $type  = $this->faker->randomElement(array_keys(CouponCode::$typeMap));
        $value = $type === CouponCode::TYPE_FIXED ? random_int(1, 150) : random_int(1, 40);

        if ($type === CouponCode::TYPE_FIXED) {
            $minAmount = $value + 0.02;
        } else {
            if (random_int(0, 100) < 40) {
                $minAmount = 0;
            } else {
                $minAmount = random_int(50, 500);
            }
        }

        return [
            'name'       => join(' ', $this->faker->words),
            'code'       => CouponCode::findAvailableCode(),
            'type'       => $type,
            'value'      => $value,
            'total'      => 150,
            'used'       => 0,
            'min_amount' => $minAmount,
            'not_before' => null,
            'not_after'  => null,
            'enabled'    => true,
        ];
    }
}
