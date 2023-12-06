<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $table = 'discounts';

    public $fillable = [
      'id',
      'percent',
      'product_id',
      'category_id',
      'company_id'
    ];

    public function product(){
        return $this->hasOne(Products::class, 'id', 'product_id');
    }
    public function warehouse(){
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }
    public function company(){
        return $this->hasOne(Company::class, 'id', 'company_id');
    }
    public function category(){
        return $this->hasOne(Category::class, 'id', 'category_id')->where('step', 0);
    }
    public function subCategory(){
        return $this->hasOne(Category::class, 'id', 'category_id')->where('step', 1);
    }
}
