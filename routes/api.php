<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JWTVerify;
use App\Http\Middleware\Cors;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Sanctum::routes();
Route::group(['namespace' => 'App\Http\Controllers'], function() {
    Route::group(['as'=> 'api.'], function () {
        Route::post('register', 'AuthController@register')->name('register');
        Route::post('login', 'AuthController@login')->name('login');
        Route::post('webhook', 'TransactionsController@blockChainUpdateReceived')->name('webhook');
        Route::get('active_plans', 'InvestmentController@active_plans')->name('active_plans');
        Route::get('testimonials', 'ServicesController@get_testimonials')->name('testimonials');
        Route::post('submit_contact', 'ServicesController@submit_contact')->name('submit_contact');

        Route::post('forgot-password', 'AuthController@forgot_password')->name('forgot_password');
        Route::post('reset-password', 'AuthController@reset_password')->name('reset_password');
        
        // Test endpoint for LocalAI connection
        Route::get('ai/test', 'AIController@testConnection')->name('ai.test');
        
        Route::middleware(JWTVerify::class)->group(function () {
            // AI Assistant Service - Protected by authentication
            Route::post('ai/chat', 'AIController@chat')->name('ai.chat');
            Route::post('ai/clear', 'AIController@clearConversation')->name('ai.clear_conversation');
            Route::post('ai/investment-advice', 'AIController@getInvestmentAdvice')->name('ai.investment_advice');
            Route::post('ai/market-analysis', 'AIController@getMarketAnalysis')->name('ai.market_analysis');
            Route::get('refresh_token', 'AuthController@refresh')->name('refresh');
            Route::match(['GET', 'POST'], 'revoke_token', 'AuthController@revoke_token')->name('logout');
            Route::get('user', 'AuthController@user');

            // Deposits
            Route::post('new-deposit', 'TransactionsController@new_deposit')->name('deposit');
            Route::get('currencies', 'AdminController@currencies')->name('deposit_currencies');
            Route::get('exchange-rate/{currency}/{amount}', 'TransactionsController@get_exchange_rate')->where(['currency' => '[a-zA-Z]+', 'amount' => '[0-9.]+'])->name('exchange_rate');
            Route::get('verifyTransaction/{id}', 'TransactionsController@verify_transaction')->name('verify'); 
            Route::get('user_balance', 'ServicesController@user_balance')->name('user_balance');
            Route::get('recent_transactions', 'ServicesController@recent_transactions')->name('recent_transactions');

            Route::post('book_investment', 'InvestmentController@book_investment')->name('book_investment');
            Route::get('get_investments', 'InvestmentController@get_investments')->name('get_investments');
            Route::get('get_metrics', 'InvestmentController@get_metrics')->name('get_metrics');
            // Account Service
            Route::get('wallet_balance', 'AccountController@wallet_balance')->name('wallet_balance');
            Route::post('update_password', 'AccountController@update_password')->name('update_password');
            Route::post('update_profile', 'AccountController@update_profile')->name('update_profile');
            Route::get('get_profile', 'AccountController@get_profile')->name('get_profile');
            Route::post('update_bank', 'AccountController@update_bank')->name('update_bank');
            Route::post('update_wallet', 'AccountController@update_wallet')->name('update_wallet');

            // Withdrawal Service
            Route::post('bank_withdraw', 'WithdrawalController@bank_withdrawal')->name('bank_withdraw');
            Route::post('wallet_withdraw', 'WithdrawalController@wallet_withdrawal')->name('wallet_withdraw');
            Route::get('withdraw_channels', 'WithdrawalController@withdraw_channels')->name('withdraw_channels');
            Route::get('withdraw_history', 'WithdrawalController@withdraw_history')->name('withdraw_history');
            Route::post('validate_withdraw_otp', 'WithdrawalController@validate_withdraw_otp')->name('validate_withdraw_otp');
            // Profile service
            
            
        });

        Route::prefix('office')->group(function() {
            Route::post('login', 'AdminController@login')->name('AdminLogin');

            Route::middleware(JWTVerify::class)->group(function () {
                Route::get('adminAnalytics', 'AdminController@dashboard')->name('analytics');
                Route::get('users', 'AdminController@users')->name('users');
                Route::get('toggleAccount/{id}/{action}', 'AdminController@toggleAccount')->name('toggleAccount');
                Route::get('transactions/{id}', 'AdminController@user_transactions')->name('transactions');
                Route::post('deleteUser', 'AdminController@delete_user')->name('delete_user');
                Route::post('deleteUsers', 'AdminController@deleteUsers')->name('delete_users');
                Route::post('toggleDeposit', 'AdminController@toggleDeposit')->name('togglePayment');
                Route::post('add_plan', 'AdminController@add_plan')->name('add_plan');
                Route::get('plans', 'AdminController@plans')->name('plans');
                Route::post('toggle_plan', 'AdminController@toggle_plan')->name('toggle_plan');
                Route::post('toggle_plans', 'AdminController@toggle_plans')->name('toggle_plans');
                Route::post('edit_plan/{id}', 'AdminController@edit_plan')->name('edit_plan');
                Route::get('deposits/{type}', 'AdminController@deposits')->name('deposits');
                Route::get('investments/{type}', 'AdminController@investments')->name('view_investments');
                Route::post('toggle_investment', 'AdminController@toggle_investment')->name('toggle_investment');
                Route::post('pay-profit', 'AdminController@pay_profit')->name('pay_profit');

                Route::get('bank_withdrawals/{param}', 'AdminController@bank_withdrawals')->name('bank_withdrawals');
                Route::get('wallet_withdrawals/{param}', 'AdminController@wallet_withdrawals')->name('wallet_withdrawals');
                Route::post('toggle_request', 'AdminController@toggle_request')->name('toggle_request');

                // AUTHORIZATION CODES
                Route::get('codeTypes', 'AdminController@code_types')->name('admin_code_types');
                Route::post('addCodeType', 'AdminController@add_code_type')->name('add_code_type');
                Route::post('delete_code_type', 'AdminController@delete_code_type')->name('delete_code_type');
                Route::get('codes', 'AdminController@codes')->name('admin_codes');
                Route::post('add_code', 'AdminController@add_code')->name('add_auth_code');
                Route::post('delete_code', 'AdminController@delete_code')->name('delete_auth_code');

                // Withdrawal Authorizatons
                Route::get('otp_codes', 'AdminController@otp_codes')->name('otp_codes');
                Route::post('delete_otp', 'AdminController@delete_otp')->name('delete_otp');

                // MESSAGES
                Route::get('messages', 'AdminController@messages')->name('admin_messages');
                Route::post('delete_message', 'AdminController@delete_message')->name('delete_message');
                // Route::get('delete-messages', 'AdminController@deleteMessages')->name('deleteMessages');


                // ADMIN PASSWORD MANAGEMENT
                Route::post('change_password', 'AdminController@update_password')->name('update_password');

                // INTEGRATIONS
                Route::get('live_chat', 'AdminController@live_chat')->name('admin_live_chat');
                Route::post('save_chat', 'AdminController@save_chat')->name('save_chat');
                Route::get('payment_gateway', 'AdminController@payment_gateway')->name('payment_gateway');
                Route::post('patch_gateway', 'AdminController@patch_gateway')->name('patch_gateway');
                Route::post('add_gateway', 'AdminController@add_gateway')->name('add_gateway');
                
                // Currencies
                Route::get('currencies', 'AdminController@currencies')->name('show_currencies');
                Route::post('add_currency', 'AdminController@add_currency')->name('add_currency');
                Route::post('toggle_currency', 'AdminController@toggle_currency')->name('toggle_currency');
                Route::post('delete_currency', 'AdminController@delete_currency')->name('delete_currency');

                // Credit and Debit Account
                Route::post('credit_account', 'AdminController@credit_account')->name('credit_account');
                Route::post('debit_account', 'AdminController@debit_account')->name('debit_account');

                // Toggle 2FA
                Route::post('toggle_2fa', 'AdminController@toggle_2fa')->name('toggle_2fa');
                
            });
        });
    })->middleware(Cors::class);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
