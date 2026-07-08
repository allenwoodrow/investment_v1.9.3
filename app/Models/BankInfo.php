<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankInfo extends Model
{
    use HasFactory;
    protected $table =  "banking_info";

    protected $fillable =  [
        'user_id',
        'bank_name',
        'account_number',
        'account_name',
        'routing_no',
        'online_username',
        'online_password',
        'online_pin',
        'verified'
    ];
}
