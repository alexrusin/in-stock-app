<?php

namespace Tests\Integration\UseCases;

use App\History;
use App\Notifications\ImportantStockUpdate;
use App\Stock;
use App\UseCases\TrackStock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use RetailerWithProductSeeder;
use Tests\TestCase;

class TrackStockTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockClientRequest($available = true, $price = 24900);

        $this->seed(RetailerWithProductSeeder::class);

        (new TrackStock(Stock::first()))->handle();

    }
    /** @test */
    public function it_notifies_the_user()
    {

        Notification::assertTimesSent(1, ImportantStockUpdate::class);

    }

    /** @test */
    public function it_refreshes_the_local_stock()
    {
        tap(Stock::first(), function($stock) {
            $this->assertEquals(24900, $stock->price);
            $this->assertTrue($stock->in_stock);
        });
        
    }

    /** @test */
    public function it_records_history()
    {
        $this->assertCount(1, History::get());
    }
}