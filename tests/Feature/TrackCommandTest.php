<?php

namespace Tests\Feature;

use App\Notifications\ImportantStockUpdate;
use App\Product;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use RetailerWithProductSeeder;
use Tests\TestCase;

class TrackCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_tracks_product_stock()
    {
        $this->seed(RetailerWithProductSeeder::class);

        $this->assertFalse(Product::first()->inStock());

        $this->mockClientRequest();
        

        $this->artisan('track');

        $this->assertTrue(Product::first()->inStock());
    }
}
