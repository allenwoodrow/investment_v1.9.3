<?php

namespace App\Models;

// removed MustVerifyEmail to disable email verification on registration
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Subscription;
use App\Models\WithdrawRequest;
use App\Models\BankInfo;
use App\Models\WalletInfo;
use App\Models\Plan;
use App\Enums\TransactionType;
use App\Models\User_2FA_Enabled;
use App\Models\AuthCode;
// use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'active',
        'email_verified_at'
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
        'password' => 'hashed'
    ];

    protected $appends = [
        "tradingBalance",
        "wallet",
        "commission",
        "profile",
        "multi",
        "hasCheckpoints"
    ];

    /**
     * Always encrypt the password when it is updated.
     *
     * @param $value
    * @return string
    */
    // public function setPasswordAttribute($value) {
    //     $this->attributes['password'] = bcrypt($value);
    // }


    public function getHasCheckpointsAttribute() {
        return $this->hasMany(AuthCode::class, 'user_id')->first() != NULL;
    }
    

    public function getMultiAttribute() {
        $enabled = User_2FA_Enabled::where('user_id', $this->id)->first();
        // $enabled = $this->hasOne(User_2FA_Enabled::class, 'user_id')->first();
        if($enabled == NUll) {
            User_2FA_Enabled::create([
                'user_id' => $this->id,
                'enabled' => false
            ]);
            return false;
        } else {
            return $enabled->enabled;
        }
    }

    // email verification removed; keep `email_verified_at` field if needed

    public function profile() {
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function account() {
        return $this->hasMany(Account::class, 'user_id');
    }

    public function bank() {
        return $this->hasOne(BankInfo::class, 'user_id');
    }

    public function payoutWallet() {
        return $this->hasOne(WalletInfo::class, 'user_id')->first();
    }

    public function active_plans() {
        return $this->hasMany(Subscription::class, 'user_id')->where('approved', true)->where('status', 'running');
    }

    public function subs() {
        return $this->hasMany(Subscription::class, 'user_id')->orderBy('created_at', 'desc');
    }

    public function withdrawals() {
        return $this->hasMany(WithdrawRequest::class, 'user_id');
    }

    public function getTradingBalanceAttribute() {
        $account = $this->account->where('type', 'trading')->first();
    
        if ($account == null) {
            return 0.00;
        }
    
        $plans = $this->active_plans->map(function($plan) {
            return $plan->payments();
        });
    
        $trans = Transaction::where('account_id', $account->id)
            ->where('type', TransactionType::TRADING_DEPOSIT)
            ->where('payment_status', 'finished')
            ->sum('price_amount');
    
        $gain = 0.00;
    
        foreach($plans as $sub) {
            $gain = $gain + floatval($sub);
        }
    
        return $trans + floatval($gain);
    }

    public function getWalletAttribute() {
        $account = Account::where('type', 'wallet')->where('user_id', $this->id)->first();
        // $account = $this->account->where('type', 'wallet')->first();
        return ($account != NULL ) ? $account->balance : 0.00;
        // return $this->account->where('type', 'wallet')->first()->balance;
    }

    public function getCommissionAttribute() {
        $account = $this->account->where('type', 'commission')->first();
        return ($account != NULL ) ? $account->balance : 0.00;
        // return $this->account->where('type', 'commission')->first()->balance;
    }

    public function getProfileAttribute() {
        return $this->profile()->first();
    }

    // public function walletAccount() {
        // $account = $this->account->where('type', 'trading')->first();
        // return ($account != NULL ) ? $account->balance : 0.00;
        // return $this->account->where('type', 'wallet')->first();
    // }
}
