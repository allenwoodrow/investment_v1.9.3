<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TransactionService;
use App\Services\AccountService;
use Illuminate\Support\Facades\Validator;

class ServicesController extends Controller
{

    function user_balance(Request $request) {
        $user = $this->GetAuthUser();
        if($user && $user != null) {
            // $data['account_id'] = $user->account->id;
            // if(!$service->logTransaction($user, $data, false, TransactionType::WALLET_DEPOSIT)) {
            //     return $this->failed($service->error);
            // }
            // $active = Subscription::where('user_id', $this->id)
    //     ->where('approved', true)
    //     ->where('status', 'running')->get();
            $subs = $user->active_plans->map(function($plan) {
                return $plan->payments();
            });
            $gain = 0.00;
            foreach($subs as $sub) {
                $gain = $gain + floatval($sub);
            }
            $data = [
                'count' => $user->active_plans->count(),
                'net_gain' => $gain
            ];  
    //     return $data;
            return $this->success([
                "trading_balance" => $user->tradingBalance,
                "active_trades" => $data,
                "wallet" => $user->wallet,
                "commission" => $user->commission
            ]);
        }

        return $this->failed('Unable to validate user, unauthenticated request');
    }

    function recent_transactions(Request $request) {
        $user = $this->GetAuthUser();
        if($user && $user != null) {
            $recent = TransactionService::recent($user->id);
            // $data['account_id'] = $user->account->id;
            // if(!$service->logTransaction($user, $data, false, TransactionType::WALLET_DEPOSIT)) {
            //     return $this->failed($service->error);
            // }
            return $this->success($recent->toArray());
        }

        return $this->failed('Unable to validate user, unauthenticated request');
    }

    function get_testimonials(Request $request) {
        $data = \App\Models\Testimonial::all();
        return $this->success($data);
    }

    function submit_contact(Request $request) {
        $form = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'name' => ['required'],
            'message' => ['required']
        ]);

        if($form->fails()) {
            return $this->failed('Please complete all form fields correctly');
        }
        $valid = $form->validated();
        \App\Models\Contact::create($valid);
        return $this->success(true);
    }
}
