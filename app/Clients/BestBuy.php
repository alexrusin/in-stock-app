<?php

namespace App\Clients;

use App\Stock;
use Illuminate\Support\Facades\Http;

class BestBuy implements Client 
{
    public function checkAvailability(Stock $stock): StockStatus
    {
        $results = Http::withOptions([
            'verify' => false,
        ])->get($this->endpoint($stock->sku))->json();

        
        $results['onlineAvailability'] = true;
        $results['salesPrice'] = 299.99;

        return new StockStatus(
            $results['onlineAvailability'],
            (int) ($results['salesPrice'] * 100)
        );
    }

    protected function endpoint($sku): string
    {
        $key = config('services.clients.bestBuy.key');
    
        return "https://api.bestbuy.com/v1/products/{$sku}.json?apiKey={$key}";
    }
}