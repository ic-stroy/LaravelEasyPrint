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
      'status',     // 1 basked 2 ordered 3 performed 4 CANCELLED 5 ACCEPTED_BY_RECIPIENT
      'delivery_date',
      'delivery_price',
      'all_price',
      'user_id',
      'coupon_id',
      'address_id',
      'coupon_price',
      'address_id',
      'receiver_name',
      'phone_number',
      'payment_method',
      'user_card_id',
      'discount_price',
      'created_at',
      'updated_at',
      'deleted_at'
    ];

    public function orderDetail(){
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function orderDetailWithTrashed(){
        return $this->hasMany(OrderDetail::class, 'order_id', 'id')->withTrashed();
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function userWithTrashed(){
        return $this->hasOne(User::class, 'id', 'user_id')->withTrashed();
    }

    public function warehouse(){
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }

    public function warehouseWithTrashed(){
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id')->withTrashed();
    }

    public function product(){
        return $this->hasOne(Warehouse::class, 'id', 'product_id');
    }

    public function productWithTrashed(){
        return $this->hasOne(Warehouse::class, 'id', 'product_id')->withTrashed();
    }

    public function coupon(){
        return $this->hasOne(Coupon::class, 'id', 'coupon_id');
    }

    public function address(){
        return $this->hasOne(Address::class, 'id', 'address_id');
    }

    public function addressWithTrashed(){
        return $this->hasOne(Address::class, 'id', 'address_id')->withTrashed();
    }
}
