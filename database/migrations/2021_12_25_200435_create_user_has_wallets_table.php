<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHasWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_has_wallets', function (Blueprint $table) {
            $table->id();
            $table->integer('wallet_id');
            $table->double('wallet_balance');
            $table->double('coins')->comment('rupees will be converted into coins according to coin unit value');
            $table->double('unit_coin_value')->comment('rupees will be converted into coins according to coin unit value');
            $table->integer('user_id');
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
        Schema::dropIfExists('user_has_wallets');
    }
}
