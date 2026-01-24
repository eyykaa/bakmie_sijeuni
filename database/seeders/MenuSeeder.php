<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // MAKANAN
            ['name'=>'Mie Cinta',      'category'=>'makanan', 'price'=>35000, 'image'=>'mie1.jpg'],
            ['name'=>'Mie Spesial',    'category'=>'makanan', 'price'=>28000, 'image'=>'mie2.jpg'],
            ['name'=>'Mie Jumbo',      'category'=>'makanan', 'price'=>32000, 'image'=>'mie3.jpg'],
            ['name'=>'Mie Komplit',    'category'=>'makanan', 'price'=>40000, 'image'=>'mie4.jpg'],
            ['name'=>'Mie Biasa',      'category'=>'makanan', 'price'=>20000, 'image'=>'mie5.jpg'],
            ['name'=>'Mie Sederhana',  'category'=>'makanan', 'price'=>25000, 'image'=>'mie6.jpg'],

            // MINUMAN
            ['name'=>'Matcha Love',    'category'=>'minuman', 'price'=>10000, 'image'=>'matcha.jpg'],
            ['name'=>'Chocolate Cute', 'category'=>'minuman', 'price'=>12000, 'image'=>'coco.jpg'],
            ['name'=>'Lemon Pretty',   'category'=>'minuman', 'price'=> 8000, 'image'=>'lemon.jpg'],
            ['name'=>'Es Teh',         'category'=>'minuman', 'price'=> 6000, 'image'=>'teh.jpg'],
            ['name'=>'Coffee',         'category'=>'minuman', 'price'=>10000, 'image'=>'coffee.jpg'],
            ['name'=>'Air Mineral',    'category'=>'minuman', 'price'=> 4000, 'image'=>'air.jpg'],

            // DESSERT
            ['name'=>'Cake Purple',     'category'=>'dessert', 'price'=>18000, 'image'=>'cake.jpg'],
            ['name'=>'Banana EsCream',  'category'=>'dessert', 'price'=>15000, 'image'=>'banana.jpg'],
            ['name'=>'Sweet EsCream',   'category'=>'dessert', 'price'=>17000, 'image'=>'sweet.jpg'],
        ];

        foreach ($items as $it) {
            Menu::updateOrCreate(
                ['name' => $it['name'], 'category' => $it['category']],
                [
                    'price' => $it['price'],
                    'image' => $it['image'],
                    'is_active' => true,
                ]
            );
        }
    }
}