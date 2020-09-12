<?php

use App\Category;
use Illuminate\Database\Seeder;
use App\Image;
use App\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(Category::class, 50)->create();
        factory(Product::class, 50)
        ->create()
        ->each(function ($product){          
            $images = factory(Image::class, mt_rand(2, 4))->make();
            $product->images()->saveMany($images);
            $categories=Category::all()->random(mt_rand(1,5))->pluck('id');
            $product->categories()->attach($categories);
        });

    }
}
