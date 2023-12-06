<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'products';
    protected $fillable = [
        'id',
        'name',
        'category_id',
        'status',
        'description'
    ];
    public function category(){
        return $this->hasOne(Category::class, 'id','category_id')->where('step', 0);
    }
    public function subCategory(){
        return $this->hasOne(Category::class, 'id','category_id')->where('step', 1);
    }
    public function subSubCategory(){
        return $this->hasOne(Category::class, 'id','category_id')->where('step', 2);
    }
    public function category_(){
        return $this->hasOne(Category::class, 'parent_id','category_id');
    }
    public function getCategory(){
        return $this->hasOne(Category::class, 'id','category_id');
    }
    public function warehouse(){
        return $this->hasMany(Warehouse::class, 'product_id', 'id');
    }
    public function order_detail(){
        return $this->hasMany(OrderDetail::class, 'product_id', 'id');
    }

    public function discount()
    {
        return $this->hasOne(Discount::class, 'product_id','id')->where('type', 1)->where('start_date', '<=', date('Y-m-d H:i:s'))->where('end_date', '>=', date('Y-m-d H:i:s'));
    }
}
