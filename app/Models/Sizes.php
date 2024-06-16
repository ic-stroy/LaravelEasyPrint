<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sizes extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sizes';
    protected $fillable = [
        'id',
        'name',
        'category_id'
    ];

    public function category(){
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function warehouse(){
        return $this->hasOne(Warehouse::class, 'size_id', 'id');
    }
}
