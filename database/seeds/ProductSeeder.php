<?php

use App\Product;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->name = $faker->words(3, true);
            $product->price = $faker->randomFloat(2, 10, 1000);
            $product->save();
        }
    }
}
