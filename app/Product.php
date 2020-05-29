<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function track()
    {
        $this->stock->each->track();
    }

    public function inStock()
    {
        return $this->stock()->whereInStock(true)->exists();
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }
}
