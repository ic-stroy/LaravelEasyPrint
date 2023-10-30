<?php

namespace App\Models;

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
    ];

    public function product(){
        return $this->hasOne(Products::class, 'id', 'product_id');
    }
}
