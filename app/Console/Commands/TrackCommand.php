<?php

namespace App\Console\Commands;

use App\Product;
use Illuminate\Console\Command;

class TrackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'track';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Track all product stock';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Product::all()
            ->tap(function($products) {
                $this->output->progressStart($products->count());
            })->each(function ($product) {
            $product->track();
            $this->output->progressAdvance();
        });

        $this->output->progressFinish();        

        $this->table(
            ['name', 'price', 'url', 'in_stock'],
            $this->showResults()
        );
    }

    protected function showResults()
    {
        return Product::leftJoin('stock', 'stock.product_id', '=', 'products.id')->get(['name', 'price', 'url', 'in_stock']);
    }
}
