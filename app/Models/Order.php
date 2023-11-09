<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';

    public $fillable = [
      'id',
      'price',
      'status',     // 1 basked 2 ordered 3 finished
      'delivery_date',
      'delivery_price',
      'all_price',
      'user_id',
      'coupon_id',
      'created_at',
      'updated_at',
      'deleted_at'
    ];

    public function order_detail(){
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }
}
