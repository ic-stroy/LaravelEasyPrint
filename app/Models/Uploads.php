<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Uploads extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'uploads';

    public $fillable = [
      'id',
      'status',
      'image',
      'relation_type',
      'relation_id',
      'created_at',
      'updated_at',
      'deleted_at'
    ];
}
