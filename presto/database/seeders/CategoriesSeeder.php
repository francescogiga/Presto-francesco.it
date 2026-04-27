<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public array $categories = [
        'Elettronica',
        'Abbigliamento',
        'Arredamento',
        'Sport',
        'Giochi',
        'Libri',
        'Auto',
        'Moto',
        'Musica',
        'Altro',
    ];

    public function run(): void
    {
        foreach ($this->categories as $name) {
            $category = new Category();
            $category->name = $name;
            $category->save();
        }
    }
}
