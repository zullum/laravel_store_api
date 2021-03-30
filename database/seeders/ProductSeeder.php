<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Models\Store;
use App\Models\Product;

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
        // geeting the all available stores
        $stores = Store::all()->pluck('id')->toArray();

        $colors = array('green', 'red', 'purple', 'orange', 'yellow', 'black', 'gray', 'white', 'brown');
        $product_names = array('T-Shirt', 'Pants', 'Jacket', 'Shirt', 'Socks', 'Short Pants', 'Cardigane', 'Sweater', 'Dress');
        $types = array('Outer wear', 'Active wear', 'Swimwear', 'Tailored clothing ', 'Casual wear ', 'Leg wear', 'Neckwear');
        $sizes = array('XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL');
        $materials = array('Cotton', 'Synthetic materials', 'Cellulosic fibres/viscose', 'Wool', 'Silk', 'Leather', 'Bast fibres');

        for ($i = 0; $i < 10; $i++) {

            $input['name'] = $faker->randomElement($product_names);
            $input['color'] = $faker->randomElement($colors);
            $input['type'] = $faker->randomElement($types);
            $input['size'] = $faker->randomElement($sizes);
            $input['material'] = $faker->randomElement($materials);
            $input['price'] = $faker->randomFloat(2, 5, 200);
            $input['quantity_in_stock'] = $faker->numberBetween(10,100);
            // picking a random manager for our first store
            $input['store_id'] = $faker->randomElement($stores);

            Product::create($input);
        }

    }
}
