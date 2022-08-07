<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');  //setting database key constains to False for truncating database
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        //defining variables to how much records to be stored
        $usersQuantity =1000;
        $categorysQuantity =30;
        $productsQuantity =1000;
        $transactonsQuantity =1000; 

        User::factory($usersQuantity)->create();
        Category::factory($categorysQuantity)->create();
        Product::factory($productsQuantity)->create()->each(
            function($product){
                $categories = Category::all()->random(mt_rand(1,5))->pluck('id');   //here we are attaching Category to Product
                $product->categories()->attach($categories);
            });
        Transaction::factory($transactonsQuantity)->create();
    }
}
