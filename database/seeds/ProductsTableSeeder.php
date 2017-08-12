<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('products')->truncate();

        $productsContent =
        [
            'Aldnoah Zero', 'Monogatari Shinobo', 'Monogatari Cover', 'Kill La Kill', 'Dragon Ball Z',

            'Danganropa', 'D.gray man', 'Laravel', 'Game Of Thrones', 'Echi Yaaa'

        ];

        $productsContentDesc =
        [
            'Aldnoah Zero S2', 'Monogatari S6', 'Monogatari Cover part2', 'Kill La Kill fucking good',

            'Dragon Ball Z Belz-Sama', 'Danganropa Mirai Arc', 'D.gray man Hollow',

            'Laravel 5.5', 'Game Of Thrones s7', 'High School Of Zombie'

        ];

        for ($i = 1; $i <= 10; $i++) {
            $products[] = [
                // Because the images start From 1 And The Array Zero Based Index
                'title' => $productsContent[( $i - 1)],
                'desc' => $productsContentDesc[( $i - 1)],
                'price' => rand(100, 500),
                'image' => 'images/' . $i . '.jpg',
            ];
        }

        DB::table('products')->insert($products);
    }
}
