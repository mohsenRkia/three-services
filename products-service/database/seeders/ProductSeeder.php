<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Infrastructure\Persistence\Product\ProductModel;


class ProductSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        ProductModel::on(env('DB_WRITE_CONNECTION'))->factory()->count(10)->create();
    }
}
