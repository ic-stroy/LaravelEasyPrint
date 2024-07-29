<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'addresses';

    public function cities(){
        return $this->hasOne(Cities::class, 'id', 'city_id');
    }
    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
