<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeTranslations extends Model
{
    use HasFactory;

    protected $table = "size_translations";
    protected $fillable = [
        'name',
        'size_id',
        'lang'
    ];
}
