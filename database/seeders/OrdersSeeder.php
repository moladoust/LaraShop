<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrdersSeeder extends Seeder
{
    public function run()
    {
        $faker = app(\Faker\Generator::class);
        $orders = Order::factory()->count(100)->create();
        $products = collect([]);
        foreach ($orders as $order) {
            $items = OrderItem::factory()->count(random_int(1, 3))->create([
                'order_id'    => $order->id,
                'rating'      => $order->reviewed ? random_int(1, 5) : null,
                'review'      => $order->reviewed ? $faker->sentence : null,
                'reviewed_at' => $order->reviewed ? $faker->dateTimeBetween($order->paid_at) : null, // 评价时间不能早于支付时间
            ]);

            $total = $items->sum(function (OrderItem $item) {
                return $item->price * $item->amount;
            });

            if ($order->couponCode) {
                $total = $order->couponCode->getAdjustedPrice($total);
            }

            $order->update([
                'total_amount' => $total,
            ]);

            $products = $products->merge($items->pluck('product'));
        }

        $products->unique('id')->each(function (Product $product) {
            $result = OrderItem::query()
                ->where('product_id', $product->id)
                ->whereHas('order', function ($query) {
                    $query->whereNotNull('paid_at');
                })
                ->first([
                    \DB::raw('count(*) as review_count'),
                    \DB::raw('avg(rating) as rating'),
                    \DB::raw('sum(amount) as sold_count'),
                ]);

            $product->update([
                'rating'       => $result->rating ?: 5,
                'review_count' => $result->review_count,
                'sold_count'   => $result->sold_count,
            ]);
        });
    }
}
