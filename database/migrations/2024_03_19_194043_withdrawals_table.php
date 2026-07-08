<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::dropIfExists('withdrawal');
        Schema::dropIfExists('withdraw_request');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        Schema::create('banking_info', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_name');
            $table->string('routing_no')->nullable();
            $table->string('online_username')->nullable();
            $table->string('online_password')->nullable();
            $table->string('online_pin')->nullable();
            $table->boolean('verified')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('wallet_info', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('address');
            $table->string('network');
            $table->string('symbol')->nullable();
            $table->string('private_key')->nullable();
            $table->boolean('verified')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('withdraw_request', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('trans_id')->nullable();
            $table->decimal('amount', 28,8);
            $table->string('type')->default('wallet');
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->unsignedBigInteger('wallet_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('trans_id')->references('id')->on('transactions');
            $table->foreign('bank_id')->references('id')->on('banking_info');
            $table->foreign('wallet_id')->references('id')->on('wallet_info');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::dropIfExists('withdraw_request');
        Schema::dropIfExists('banking_info');
        Schema::dropIfExists('wallet_info');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
