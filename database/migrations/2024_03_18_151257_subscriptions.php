<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Subscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plans');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('investment_term')->nullable();
            $table->decimal('min_amount', 28,8);
            $table->decimal('return_percent', 28,8)->default(3.00);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plan_id');
            $table->decimal('amount');
            $table->string('status')->default(false);
            $table->boolean('approved')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('subscriptions');
    }
}
