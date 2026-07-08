<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use App\Models\User;
use App\Utils\Meta;
use App\Models\BankInfo;
use App\Models\WalletInfo;
use App\Models\Transaction;

class WithdrawRequest extends Model
{
    use HasFactory;

    protected $table = "withdraw_request";

    protected $fillable = [
        'user_id',
        'trans_id',
        'amount',
        'type',
        'status',
        'bank_id',
        'wallet_id'
    ];

    protected $appends = [
        'formatedAmount',
        'badge',
        'bank',
        'wallet'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'trans_id');
    }

    public function getFormatedAmountAttribute() {
        return Meta::money($this->amount);
    }

    public function getBankAttribute() {
        return ($this->type == 'bank') ? $this->bank_info : NULL;
    }

    public function getWalletAttribute() {
        return ($this->type == 'wallet') ? $this->wallet_info : NULL;
    }

    public function bank_info() {
        return $this->belongsTo(BankInfo::class, 'bank_id');
    }

    public function wallet_info() {
        return $this->belongsTo(WalletInfo::class, 'wallet_id');
    }

    public function getBadgeAttribute() {
        switch($this->status) {
            case 'pending':
                return new HtmlString('<div class="badge badge-warning">Pending</div>');
            case 'approved':
                return new HtmlString('<div class="badge badge-success">Approved</div>');
            case 'cancelled':
                return new HtmlString('<div class="badge badge-danger">Rejected</div>');
        }
    }
}
