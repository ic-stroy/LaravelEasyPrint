<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'   // 1.Super admin 2.Admin 3.Manager 4.User
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function personalInfo(){
        return $this->hasOne(PersonalInfo::class, 'id', 'personal_info_id');
    }
    public function role(){
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
    public function address(){
        return $this->hasOne(Address::class, 'id', 'address_id');
    }
    public function company(){
        return $this->hasOne(Company::class, 'id', 'company_id');
    }
    public function orderBasket(){
        return $this->hasOne(Order::class, 'user_id', 'id')->where('status', 1);
    }
}
