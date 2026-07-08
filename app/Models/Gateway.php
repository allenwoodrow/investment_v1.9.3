<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Crypt;

class Gateway extends Model
{
    use HasFactory;

    protected $table = 'gateway';

    protected $fillable = [
        'publicKey', 'privateKey'
    ];

    // protected $appends = [
    //     'safeKey'
    // ];

    // public function setPrivateKeyAttribute($value) {
    //     $this->attributes['privateKey'] = Crypt::encryptString($value);
    // }

    // public function getSafeKeyAttribute() {
    //     try {
    //         $decrypted = Crypt::decryptString($this->privateKey);
    //         return $decrypted;
    //     } catch (DecryptException $e) {
    //         return 'failed to decrypt';
    //     }
        
    // }
}
