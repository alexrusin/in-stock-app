<?php

namespace App;

use App\UseCases\TrackStock;
use Illuminate\Database\Eloquent\Model;


class Stock extends Model
{
    protected $table = 'stock';

    protected $casts = [
        'in_stock' => 'boolean'
    ];

    public function track()
    {
        TrackStock::dispatch($this);
    }

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
