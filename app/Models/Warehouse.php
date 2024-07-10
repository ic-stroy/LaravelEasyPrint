<?php

namespace App\Models;

use App\Constants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Warehouse extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'warehouses';
    protected $fillable = [
        'name',
        'product_id',
        'company_id',
        'color_id',
        'size_id',
        'price',
        'quantity',
        'status',
        'images',
        'description',
        'material_id',
        'manufacturer_country',
        'material_composition',
    ];

    public function product(){
        return $this->hasOne(Products::class, 'id', 'product_id');
    }

    public function size(){
        return $this->hasOne(Sizes::class, 'id', 'size_id');
    }

    public function color(){
        return $this->hasOne(Color::class, 'id', 'color_id');
    }

    public function discount()
    {
        return $this->hasOne(Discount::class, 'warehouse_id','id')->where('type', Constants::DISCOUNT_WAREHOUSE_TYPE)->where('start_date', '<=', date('Y-m-d H:i:s'))->where('end_date', '>=', date('Y-m-d H:i:s'));
    }

    public function uploads(){
        return $this->hasMany(Uploads::class, 'relation_id', 'id');
    }

    public function company(){
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function product_discount()
    {
        return $this->hasOne(Discount::class, 'product_id','product_id')->where('type', Constants::DISCOUNT_PRODUCT_TYPE)->where('start_date', '<=', date('Y-m-d H:i:s'))->where('end_date', '>=', date('Y-m-d H:i:s'));
    }

    public function discount_withouth_expire()
    {
        return $this->hasOne(Discount::class, 'warehouse_id','id')->where('type', Constants::DISCOUNT_WAREHOUSE_TYPE)->orderBy('end_date', 'desc');
    }

    public function product_discount_withouth_expire()
    {
        return $this->hasOne(Discount::class, 'product_id','product_id')->where('type', Constants::DISCOUNT_PRODUCT_TYPE)->orderBy('end_date', 'desc');
    }

    public function order_detail(){
        return $this->hasOne(OrderDetail::class, 'warehouse_id', 'id')->where('status', 6);
    }
}
