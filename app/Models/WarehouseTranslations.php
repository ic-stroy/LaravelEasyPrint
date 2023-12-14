<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseTranslations extends Model
{
    use HasFactory;
    protected $table = "warehouse_translations";
    protected $fillable = [
        'name',
        'warehouse_id',
        'lang'
    ];
}
