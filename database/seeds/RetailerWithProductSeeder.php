<?php

use App\Product;
use App\Retailer;
use App\Stock;
use App\User;
use Illuminate\Database\Seeder;

class RetailerWithProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $switch = Product::create(['name' => 'Nintendo Switch']);

        $bestBuy = Retailer::create(['name' => 'Best Buy']);


        $bestBuy->addStock($switch, new Stock([
            'price' => 1000,
            'url' => 'http://foo.com',
            'sku' => '1234',
            'in_stock' => false
        ]));

        factory(User::class)->create(['email' => 'alex@example.com']);
    }
}
