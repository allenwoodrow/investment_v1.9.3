<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class OTPCode extends Model
{
    use HasFactory;
    protected $table = "otp_code";

    protected $fillable = [
        'user_id', 'code'
    ];

    protected $appends = [
        'owner'
    ];

    public function getOwnerAttribute() {
        $user = $this->user;
        return ['username' => $user->username, 'id' => $user->id ];
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
