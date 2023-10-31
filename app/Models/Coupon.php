<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'coupons';

    protected $fillable = [
        'percent',
        'price',
        'category_id',
        'warehouse_id',
        'company_id'
    ];
}
