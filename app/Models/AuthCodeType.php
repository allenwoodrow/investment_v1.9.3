<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AuthCode;

class AuthCodeType extends Model
{
    use HasFactory;
    protected $table = 'code_type';

    protected $fillable = [
        'name'
    ];

    public function codes() {
        return $this->hasMany(AuthCode::class, 'type_id');
    }

}
