<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public $sub_categories = ['Unisex', 'Women', 'Men', 'Children'];

    public $categories = ['T-shirts', 'Sweatshirts', 'Hoodies', 'Accessories'];

    public function run(): void
    {
        $category_id = Category::withTrashed()->select('id')->orderBy('id', 'desc')->first();
        $category_id_ = isset($category_id->id)?$category_id->id:0;
        $sub_category_id_ = 0;
        $last_category_id = -1;
        foreach ($this->categories as $category){
            $category_id_++;
            if($last_category_id < $sub_category_id_){
                $sub_category_id_ = $category_id_ + count($this->categories);
            }else{
                $sub_category_id_ = $last_category_id;
            }
            $all_categories[] = ['id'=>$category_id_, 'name'=>$category, 'step'=>0, 'parent_id'=>0];
            foreach ($this->sub_categories as $sub_category){
                $sub_category_id_++;
                $last_category_id = $sub_category_id_;
                $all_sub_categories[] = ['id'=>$sub_category_id_, 'name'=>$sub_category, 'step'=>1, 'parent_id'=>$category_id_];
            }
        }
        $all_categories_ = array_merge($all_categories, $all_sub_categories);
        foreach ($all_categories_ as $all_category){
            Category::create($all_category);
        }
    }
}
