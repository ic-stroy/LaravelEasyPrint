<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory;

    public $table = 'companies';

    public function address(){
        return $this->hasOne(Address::class, 'id', 'address_id');
    }
}
