<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;

    public $table = 'roles';

    public function user(){
        return $this->hasOne(User::class, 'role_id', 'id');
    }

}
