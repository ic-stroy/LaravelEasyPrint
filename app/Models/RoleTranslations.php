<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleTranslations extends Model
{
    use HasFactory;

    protected $table = "role_translations";
    protected $fillable = [
        'name',
        'role_id',
        'lang'
    ];
}
