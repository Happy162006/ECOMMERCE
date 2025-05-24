<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Category::insert([
            ['nombre' => 'Tecnología'],
            ['nombre' => 'Muebles'],
            ['nombre' => 'Comida']
        ]);
        
        Product::insert([
            [
                'nombre' => 'Laptop',
                'precio' => 799.99,
                'stock' => 10,
                'imagen' => 'img/laptop.jpg',
                'categoria_id' => 1
            ],
            [
                'nombre' => 'Smartphone',
                'precio' => 499.99,
                'stock' => 15,
                'imagen' => 'img/smartphone.jpg',
                'categoria_id' => 1
            ]
        ]);
    }
}