<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        // Tasas de referencia: unidades de la divisa por 1 USD
        $currencies = [
            ['code' => 'USD', 'name' => 'Dólar estadounidense', 'symbol' => '$', 'exchange_rate' => 1],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'exchange_rate' => 0.92],
            ['code' => 'COP', 'name' => 'Peso colombiano', 'symbol' => '$', 'exchange_rate' => 4000],
            ['code' => 'MXN', 'name' => 'Peso mexicano', 'symbol' => '$', 'exchange_rate' => 17.5],
            ['code' => 'ARS', 'name' => 'Peso argentino', 'symbol' => '$', 'exchange_rate' => 900],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(['code' => $currency['code']], $currency);
        }
    }
}
