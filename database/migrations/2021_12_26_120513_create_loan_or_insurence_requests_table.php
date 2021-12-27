<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanOrInsurenceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_or_insurence_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('request_type_id');
            $table->integer('user_id');
            $table->string('request_type')->comment('loan or insurance');
            $table->tinyinteger('status')->default(0)->comment('By default it wil be pending = 0, Rejected = 0 , Accepted = 1');
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
        Schema::dropIfExists('loan_or_insurence_requests');
    }
}
