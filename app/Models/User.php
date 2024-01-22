<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Constants;
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
        return $this->hasOne(Address::class, 'user_id', 'id');
    }
    public function addresses(){
        return $this->hasMany(Address::class, 'user_id', 'id');
    }
    public function company(){
        return $this->hasOne(Company::class, 'id', 'company_id');
    }
    public function allOrders(){
        return $this->hasMany(Order::class, 'user_id', 'id')->where('status', '!=', Constants::ACCEPTED);
    }
    public function orderBasket(){
        return $this->hasMany(Order::class, 'user_id', 'id')->whereIn('status', [Constants::BASKED, Constants::BASKED]);
    }
    public function order(){
        return $this->hasOne(Order::class, 'user_id', 'id')->where('status', Constants::ORDERED);
    }
    public function ordersOrdered(){
        return $this->hasMany(Order::class, 'user_id', 'id')->where('status', Constants::ORDERED);
    }
    public function ordersPerformed(){
        return $this->hasMany(Order::class, 'user_id', 'id')->where('status', Constants::PERFORMED);
    }
    public function ordersAccepted(){
        return $this->hasMany(Order::class, 'user_id', 'id')->where('status', Constants::ACCEPTED);
    }
    public function ordersCancelled(){
        return $this->hasMany(Order::class, 'user_id', 'id')->where('status', Constants::CANCELLED);
    }
    public function ordersAcceptedByRecipient(){
        return $this->hasMany(Order::class, 'user_id', 'id')->where('status', Constants::ACCEPTED_BY_RECIPIENT);
    }
    public function orders(){
        return $this->hasMany(Order::class, 'user_id', 'id')->whereIn('status', [Constants::PERFORMED, Constants::CANCELLED, Constants::ACCEPTED_BY_RECIPIENT]);
    }
}
