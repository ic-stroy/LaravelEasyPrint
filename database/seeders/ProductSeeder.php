<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\PersonalInfo;
use App\Models\Products;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $is_exist_product = Products::whereIn('name', ['Футболка', 'Свитшот', 'Худи'])->withTrashed()->first();
        $categories = Category::where('step', 0)->whereIn('name', ['T-shirts', 'Sweatshirts', 'Hoodies'])->get();
            if(!empty($categories)) {
                foreach ($categories as $category){
                    switch ($category->name) {
                        case 'T-shirts':
                            $products[] = [
                                'category_id' => $category->id,
                                'name' => 'Футболка',
                            ];
                            break;
                        case 'Sweatshirts':
                            $products[] = [
                                'category_id' => $category->id,
                                'name' => 'Свитшот',
                            ];
                            break;
                        case 'Hoodies':
                            $products[] = [
                                'category_id' => $category->id,
                                'name' => 'Худи',
                            ];
                            break;
                    }
                }

            }
        if(!$is_exist_product){
            foreach ($products as $product){
                Products::create($product);
            }
        }else{
                echo "Products are exist";
        }
    }
}
