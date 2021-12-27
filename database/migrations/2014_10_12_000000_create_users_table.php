<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('email',100)->unique();
            $table->string('phone',50);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password',150);
            $table->string('adhar_pan',30);
            $table->string('annual_income',50);
            $table->integer('agent');
            $table->date('dob');
            $table->mediumText('address');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
