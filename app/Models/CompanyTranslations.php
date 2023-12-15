<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyTranslations extends Model
{
    use HasFactory;

    protected $table = "company_translations";

    protected $fillable = [
        'name',
        'company_id',
        'lang'
    ];
}
