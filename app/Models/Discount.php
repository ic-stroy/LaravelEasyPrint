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
}
