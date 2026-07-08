<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AuthCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $this->down();
        Schema::create('code_type', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('auth_code', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("type_id");
            $table->unsignedBigInteger("request_id")->nullable();
            $table->string("code");
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('type_id')->references('id')->on('code_type');
            $table->foreign('request_id')->references('id')->on('withdraw_request');
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
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::dropIfExists('code_type');
        Schema::dropIfExists('auth_code');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
