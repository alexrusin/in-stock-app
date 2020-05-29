<?php

namespace Tests\Feature\Clients;

use App\Clients\BestBuy;
use App\Stock;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use RetailerWithProductSeeder;
use Tests\TestCase;

/** @group api */
class BestBuyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @doesNotPerformAssertions
     */
    public function it_tracks_a_product()
    {
        $this->seed(RetailerWithProductSeeder::class);

        $stock = tap(Stock::first())->update([
            'sku' => '6364253',
            'url' => 'https://www.bestbuy.com/site/nintendo-switch-32gb-console-gray-joy-con/6364253.p?skuId=6364253'
        ]);

        try {
            (new BestBuy())->checkAvailability($stock);
        } catch (Exception $e) {
            $this->fail($e);
        }       
    }

    /** @test */
    public function it_creates_the_proper_stock_status_response()
    {
        Http::fake(function() {
            return ['salePrice' => 299.99, 'onlineAvailability' => true];
        });


        $stockStatus = (new BestBuy())->checkAvailability(new Stock);

        $this->assertEquals(29999, $stockStatus->price);
    }
}