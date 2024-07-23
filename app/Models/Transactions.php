<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;

    public $table = 'transactions';

    protected $fillable = [
      'paycom_transaction_id',
      'paycom_time',
      'paycom_time_datetime',
      'create_time',
      'create_time_unix',
      'perform_time',
      'perform_time_unix',
      'cancel_time',
      'cancel_time_unix',
      'amount',
      'state',
      'reason',
      'receivers',
      'order_id'
    ];
}
