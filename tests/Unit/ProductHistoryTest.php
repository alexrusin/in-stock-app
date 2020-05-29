<?php

namespace Tests\Unit;

use App\Clients\StockStatus;
use App\History;
use App\Product;
use App\Stock;
use Facades\App\Clients\ClientFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use RetailerWithProductSeeder;
use Tests\TestCase;

class ProductHistoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_records_history_each_time_stock_is_tracked()
    {
        $this->seed(RetailerWithProductSeeder::class);

        ClientFactory::shouldReceive('make->checkAvailability')
            ->andReturn(new StockStatus($available = true, $price = 99));

        $product = tap(Product::first(), function($product) {
            
            $this->assertCount(0, $product->history);

            $product->track();

            $this->assertCount(1, $product->refresh()->history);

        });
        
        $history = $product->history->first();

        $this->assertEquals($price, $history->price);
        $this->assertEquals($available, $history->in_stock);
        $this->assertEquals($product->id, $history->product_id);
        $this->assertEquals($product->stock[0]->id, $history->stock_id);

    }
}
