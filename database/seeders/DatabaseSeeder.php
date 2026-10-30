<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CurrencySeeder::class);

        // Productos de ejemplo en dólares
        $usd = Currency::where('code', 'USD')->first();

        Product::factory()->count(5)->create(['currency_id' => $usd->id]);
    }
}
