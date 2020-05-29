<?php

namespace App\UseCases;

use App\History;
use App\Notifications\ImportantStockUpdate;
use App\Stock;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TrackStock implements ShouldQueue
{
    use Dispatchable;

    protected $stock;

    /** @var StockStatus */
    protected $status;

    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
    }

    public function handle()
    {
        $this->checkAvailability();
        $this->notifyUser();
        $this->refreshStock();
        $this->recordToHistory();
    }
    
    public function checkAvailability()
    {
         $this->status = $this->stock->retailer
            ->client()
            ->checkAvailability($this->stock);
    }

    public function notifyUser()
    {
        if ($this->isNowInStock()) {
            User::first()->notify(new ImportantStockUpdate($this->stock));
        }
    }

    protected function isNowInStock()
    {
        return ! $this->stock->in_stock && $this->status->available;
    }

    public function refreshStock()
    {
        $this->stock->update([
            'in_stock' => $this->status->available,
            'price' => $this->status->price
        ]);
    }

    public function recordToHistory()
    {
        History::create([
            'price' => $this->stock->price,
            'in_stock' => $this->stock->in_stock,
            'stock_id' => $this->stock->id,
            'product_id' => $this->stock->product_id
        ]);
    }
}