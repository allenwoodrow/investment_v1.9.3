<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class User_2FA_Enabled extends Model
{
    use HasFactory;

    protected $table = '2fa_enabled';

    protected $fillable = [
        'user_id',
        'enabled'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
