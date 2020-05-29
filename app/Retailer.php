<?php

namespace App;

use Facades\App\Clients\ClientFactory;
use Illuminate\Database\Eloquent\Model;

class Retailer extends Model
{
    public function addStock(Product $product, Stock $stock): void
    {
        $stock->product_id = $product->id;
        $this->stock()->save($stock);
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    public function client()
    {
        return ClientFactory::make($this);
    }
}
