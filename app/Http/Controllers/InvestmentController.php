<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Services\TransactionService;
use App\Enums\TransactionType;

class InvestmentController extends Controller {
    
    public function active_plans() {
        $plans = Plan::where('active', true)->with('details')->get();
        return $this->success($plans);
    }

    public function book_investment(Request $request) {
        $validator = Validator::make($request->all(), [
            'plan_id' => ['required'],
            'amount' => ['required']
        ]);

        if($validator->fails()) {
            return $this->failed($validator->errors()->all());
        } else {
            $data = $validator->validated();
            $plan = Plan::where('id', $data['plan_id'])->first();
            $user = $this->GetAuthUser();
            if($plan == NULL || $user == NULL) {
                return $this->failed('Unable to validate plan', ['Unable to validate plan']);
            } else {
                $sub = Subscription::create([
                    "user_id" => $user->id,
                    "plan_id" => $plan->id,
                    "status" => 'pending',
                    "amount" => $data['amount']
                ]);
                if($sub->id != NULL) {
                    $account = $user->account->where('type', 'wallet')->first();
                    
                    $transaction = Transaction::create([
                        'payment_id' => TransactionService::id(),
                        'user_id' => $user->id,
                        'account_id' => $account->id,
                        'price_amount' => $data['amount'],
                        'pay_amount' => $data['amount'],
                        'price_currency' => 'USD',
                        'pay_currency' => 'USD',
                        'order_id' => NULL,
                        'order_description' => 'Investment Payment',
                        'sub_id' => $sub->id,
                        'is_debit' => true,
                        'type' => TransactionType::SUBSCRIPTION,
                        'payment_status' => 'waiting',
                        'pay_address' => NULL,
                        'network' => 'LOCAL',
                        'expiration_estimate_date' => NULL
                    ]);

                    if($transaction->id != null) {
                        return $this->success(true);
                    }
                    // if(!$service->debitAccount($account, $data['amount'], $sub->id, 'Investment payment', TransactionType::SUBSCRIPTION)) {
                    //     $sub->delete();
                    //     return $this->failed($service->error);
                    // } else {
                    //     $service->creditServiceAccount($data['amount'], $account->type, $sub->id, 'Investment payment', TransactionType::SUBSCRIPTION);
                        
                    // }
                }

                return $this->failed('Failed to book investment');

                
            }

        }
    }

    public function get_investments(Request $request) {
        $user = $this->GetAuthUser();
        if($user != NULL) {
            $investments = Subscription::where('user_id', $user->id)->orderBy('created_at', 'DESC')->paginate(50);
            return $this->success($investments);
        } else {
            return $this->failed('Unable to validate user');
        }
    }

    public function get_metrics(Request $request) {
        $user = $this->GetAuthUser();
        if($user !== NULL) {
            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $metrics = [
                "labels" => $months,
                "datasets" => []
            ];

            
            return $this->success($metrics);
        } else {
            return $this->failed('Unable to validate user');
        }
        // export interface ProfitMetrics {
        //     labels: string[]
        //     datasets: ProfitMetric[]
        // }
        
        // export interface ProfitMetric {
        //     label: 'First Dataset'
        //     data: number[]
        //     fill: boolean
        //     // borderColor: string,
        //     tension: number
        // }
    }
}
