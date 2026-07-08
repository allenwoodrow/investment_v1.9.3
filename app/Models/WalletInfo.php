<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletInfo extends Model
{
    use HasFactory;

    protected $table = 'wallet_info';

    protected $fillable = [
        'user_id',
        'address',
        'network',
        'symbol',
        'private_key',
        'verified',
    ];
}
