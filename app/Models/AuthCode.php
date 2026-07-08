<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthCode extends Model
{
    use HasFactory;
    protected $table = 'auth_code';

    protected $fillable = [
        "user_id",
        "type_id",
        "code",
        "request_id"
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function request() {
        return $this->belongsTo(WithdrawRequest::class);
    }

    public function type() {
        return $this->belongsTo(\App\Models\AuthCodeType::class, 'type_id');
    }
}
