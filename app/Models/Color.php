<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'colors';

    public function warehouse(){
        return $this->hasOne(Warehouse::class, 'color_id', 'id');
    }
}
