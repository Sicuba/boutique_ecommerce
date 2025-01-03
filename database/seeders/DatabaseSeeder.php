<?php

use Illuminate\Database\Seeder;
use Database\Seeders\CategoryTableSeeder;
use Database\Seeders\ProductTableSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(ProductTableSeeder::class);
         $this->call(CategoryTableSeeder::class);
    }
}
