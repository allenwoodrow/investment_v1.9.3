<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Console\Scheduling\Schedule;

use App\Models\User;
use App\Models\Plan;
use App\Models\Payout;

use App\Jobs\PayoutProfits;
use App\Jobs\PruneSubscriptions;

use App\Enums\TransactionType;

use Carbon\Carbon;
use InvalidArgumentException;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "subscriptions";

    protected $fillable = [
        "user_id",
        "plan_id",
        "status",
        "amount"
    ];

    protected $appends = [
        'pnl', 'plan', 'totalProfit', 'maturity', 'client'
    ];

    public function getClientAttribute() {
        return $this->user();
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getPlanAttribute() {
        return $this->linked_plan()->first();
    }

    public function linked_plan() {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function badge() {
        if(isset($this->approved)) {
            return $this->approved == true ? "bg-success" : "bg-danger";
        } else {
            return "bg-warning";
        }
    }

    public function current_status() {
        // Double negative? Yes!!, please leave like this, unless you dey
        // find bugs wey no lost, this is by design.
        if(isset($this->approved)) {
            return $this->approved == true ? "Approved" : "Rejected";
        } else {
            return "Pending";
        }
    }

    public function start() {
        $this->approved_at = Carbon::now();
        $this->approved = true;
        $this->status = true;
        $this->user->credit_active($this->amount);
        $this->save();

        $invest_amount = $this->amount;
        $percentage = $this->plan->returns;
        $total_profit = ($percentage / 100) * $invest_amount;
        $startDate = Carbon::parse($this->approved_at);
        $endDate = $startDate->copy()->addDays($this->plan->duration);
        $hoursDifference = $endDate->diffInHours($startDate);
        $fractionHourly = $total_profit / $hoursDifference;

        for ($i = 0; $i <= $hoursDifference; $i++) {
            $now = $startDate->copy()->addMinutes(1);
            PayoutProfits::dispatch($fractionHourly, $this->id)->delay($now);
            $startDate->addHours(1);
        }
        PruneSubscriptions::dispatch($this->id)->delay($endDate);
    }

    public function deleteAll() {
       
        // $date = Carbon::parse($this->approved_at)->addDays($this->plan->duration);
    }

    public function reject() {
        $this->approved = false;
        $this->status = false;
        $this->user->credit_trading($this->amount);
        $this->save();
    }

    public function maturity_date() {
        if($this->approved != NULL && $this->approved_at != NULL) {
            if($this->approved == true) {
                $plan = $this->linked_plan()->first();

                if($plan == NULL) {
                    return "Plan not found";
                }

                try {
                    $days = $plan->investmentTermInDays();
                } catch (InvalidArgumentException $e) {
                    return "Invalid investment term";
                }

                $date = Carbon::parse($this->approved_at)->addDays($days);
                return $date->toDateString();
            } else {
                return "Not approved";
            }
        }
            
        return "Not approved";
    }

    public function payouts() {
        return $this->hasMany(Payout::class, 'sub_id');
    }

    public function getPnlAttribute() {
        return $this->amount + $this->total_profit();
    }

    public function getTotalProfitAttribute() {
        return $this->total_profit();
    }

    public function getMaturityAttribute() {
        return $this->maturity_date();
    }

    public function payments() {
        $plans = $this->hasMany(Transaction::class, 'sub_id')
        ->where('type', TransactionType::PROFIT_PAY)
        ->where('payment_status', 'finished')->sum('price_amount');
        return $plans;
    }

    public function total_profit() {
        // $payouts = $this->hasMany(Payout::class, 'sub_id');
        // return $payouts->sum('amount') ?? 0.00;
        return $this->payments();
    }
}
