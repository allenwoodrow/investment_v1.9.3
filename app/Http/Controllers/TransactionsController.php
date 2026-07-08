<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Services\TransactionService;
use App\Services\JWTService;
use App\Enums\TransactionType;


class TransactionsController extends Controller
{
    //
    public function new_deposit(Request $request) {
        try {
            \Log::info('New deposit request', ['payload' => $request->all()]);

            $form = Validator::make($request->all(), [
                'payment_id' => ['required'],
                'price_amount' => ['required', 'decimal:0,4'],
                'pay_amount' => ['required', 'decimal:0,8'],
                'price_currency' => ['required', 'string'],
                'pay_currency' => ['required', 'string'],
                'order_description' => ['nullable'],
                'order_id' => ['nullable'],
                'sub_id' => ['nullable'],
                'payment_status' => ['required', 'string'],
                'pay_address' => ['required', 'string'],
                'expiration_estimate_date' => ['required', 'string'],
                'network' => ['required', 'string']
            ]);
            
            if ($form->fails()) {
                $errors = $form->errors()->all();
                \Log::warning('New deposit validation failed', ['errors' => $errors]);
                return $this->failed('Unable to validate transaction', $errors);
            }

            $data = $form->validated();
            $service = new TransactionService;
            $user = $this->GetAuthUser();

            if (!$user) {
                \Log::warning('New deposit unauthenticated request');
                return $this->failed('Unable to validate user, unauthenticated request');
            }

            $walletAccount = $user->account->where('type', 'wallet')->first();
            if (!$walletAccount) {
                \Log::warning('New deposit missing wallet account', ['user_id' => $user->id]);
                return $this->failed('Unable to find wallet account for user');
            }

            $data['account_id'] = $walletAccount->id;
            if (!$service->logTransaction($user, $data, false, TransactionType::WALLET_DEPOSIT)) {
                \Log::error('New deposit failed to log transaction', ['error' => $service->error]);
                return $this->failed($service->error);
            }

            return $this->success([
                'message' => 'Deposit queued, awaiting payment'
            ]);
        } catch (\Exception $e) {
            \Log::error('New deposit exception: ' . $e->getMessage(), [
                'payload' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->error('Server error creating deposit: ' . $e->getMessage());
        }
    }

    public function get_exchange_rate(Request $request, $currency, $amount) {
        try {
            // Validate and sanitize inputs
            $currency = trim($currency);
            $amount = (float)$amount;

            \Log::info('Exchange rate request', ['currency' => $currency, 'amount' => $amount]);

            if (empty($currency) || !is_numeric($amount) || $amount <= 0) {
                \Log::warning('Invalid exchange rate parameters', ['currency' => $currency, 'amount' => $amount]);
                return $this->failed('Invalid currency or amount provided');
            }

            $rateUsd = null;
            $currencyKey = strtolower(str_replace(' ', '-', trim($currency)));

            // Primary exchange-rate provider: CoinGecko.
            try {
                \Log::info('Fetching from CoinGecko', ['currency' => $currencyKey]);
                $response = Http::timeout(10)->get('https://api.coingecko.com/api/v3/simple/price', [
                    'ids' => $currencyKey,
                    'vs_currencies' => 'usd',
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data[$currencyKey]['usd'])) {
                        $rateUsd = (float)$data[$currencyKey]['usd'];
                    } else {
                        \Log::warning('Invalid CoinGecko response structure', ['data' => $data]);
                    }
                } else {
                    \Log::warning('CoinGecko API failed', ['status' => $response->status(), 'body' => $response->body()]);
                }
            } catch (\Exception $e) {
                \Log::warning('CoinGecko exception', ['message' => $e->getMessage()]);
            }

            // Fallback provider: CoinCap.
            if (!$rateUsd) {
                $url = 'https://api.coincap.io/v2/rates/' . $currencyKey;
                try {
                    \Log::info('Fetching from CoinCap', ['url' => $url]);
                    $response = Http::withOptions(['verify' => false])->timeout(10)->get($url);

                    if ($response->successful()) {
                        $data = $response->json();
                        if (isset($data['data']['rateUsd'])) {
                            $rateUsd = (float)$data['data']['rateUsd'];
                        } else {
                            \Log::warning('Invalid CoinCap response structure', ['data' => $data]);
                        }
                    } else {
                        \Log::warning('CoinCap API failed', ['status' => $response->status(), 'body' => $response->body()]);
                    }
                } catch (\Exception $e) {
                    \Log::warning('CoinCap exception', ['message' => $e->getMessage()]);
                }
            }

            if (!$rateUsd) {
                return $this->failed('Unable to fetch exchange rate from API providers');
            }

            $payAmount = $amount / $rateUsd;

            $result = [
                'amount' => number_format($payAmount, 8, '.', ''),
                'rate' => $rateUsd
            ];

            \Log::info('Exchange rate calculated', $result);

            return $this->success($result);

        } catch (\Exception $e) {
            \Log::error('Exchange rate exception: ' . $e->getMessage(), [
                'currency' => $currency ?? 'unknown',
                'amount' => $amount ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            return $this->failed('Server error calculating exchange rate: ' . $e->getMessage());
        }
    }

    public function blockChainUpdateReceived(Request $request) {
        // $error_msg = "Unknown error";
        // $auth_ok = false;
        // $request_data = null;
        // if (isset($_SERVER['HTTP_X_NOWPAYMENTS_SIG']) && !empty($_SERVER['HTTP_X_NOWPAYMENTS_SIG'])) {
        //     $recived_hmac = $_SERVER['HTTP_X_NOWPAYMENTS_SIG'];
        //     $request_json = file_get_contents('php://input');
        //     $request_data = json_decode($request_json, true);
        //     ksort($request_data);
        //     $sorted_request_json = json_encode($request_data);
        //     if ($request_json !== false && !empty($request_json)) {
        //         $hmac = hash_hmac("sha512", $sorted_request_json, trim($this->ipn_secret));
        //         if ($hmac == $recived_hmac) {
        //             $auth_ok = true;
        //         } else {
        //             $error_msg = 'HMAC signature does not match';
        //         }
        //     } else {
        //         $error_msg = 'Error reading POST data';
        //     }
        // } else {
        //     $error_msg = 'No HMAC signature sent.';
        // }
    }
}
