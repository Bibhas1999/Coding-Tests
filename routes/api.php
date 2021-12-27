<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/user/signup', [App\Http\Controllers\Api\V1\UsersController::class, 'createUser']);
Route::get('/users', [App\Http\Controllers\Api\V1\UsersController::class, 'listUsers']);
Route::post('/user/login', [App\Http\Controllers\Api\V1\UsersController::class, 'UserLogin']);

Route::post('/loan/create', [App\Http\Controllers\Api\V1\LoanController::class, 'create']);
Route::patch('/loan/{id}', [App\Http\Controllers\Api\V1\LoanController::class, 'update']);
Route::get('/loan/{id}', [App\Http\Controllers\Api\V1\LoanController::class, 'getProjectDetails']);
Route::delete('/loan/{id}', [App\Http\Controllers\Api\V1\LoanController::class, 'delete']);
Route::get('/loans', [App\Http\Controllers\Api\V1\LoanController::class, 'index']);

Route::post('/insurance/create', [App\Http\Controllers\Api\V1\InsuranceController::class, 'create']);
Route::patch('/insurance/{id}', [App\Http\Controllers\Api\V1\InsuranceController::class, 'update']);
Route::get('/insurance/{id}', [App\Http\Controllers\Api\V1\InsuranceController::class, 'getProjectDetails']);
Route::delete('/insurance/{id}', [App\Http\Controllers\Api\V1\InsuranceController::class, 'delete']);
Route::get('/insurances', [App\Http\Controllers\Api\V1\InsuranceController::class, 'index']);

Route::post('/transaction/create', [App\Http\Controllers\Api\V1\TransactionController::class, 'create']);
Route::patch('/transaction/{id}', [App\Http\Controllers\Api\V1\TransactionController::class, 'update']);
Route::get('/transaction/{id}', [App\Http\Controllers\Api\V1\TransactionController::class, 'getProjectDetails']);
Route::delete('/transaction/{id}', [App\Http\Controllers\Api\V1\TransactionController::class, 'delete']);
Route::get('/transactions', [App\Http\Controllers\Api\V1\TransactionController::class, 'index']);

//routes for wallet operation

Route::post('/wallet/create', [App\Http\Controllers\Api\V1\WalletController::class, 'create']); //route for creating a wallet
Route::patch('/update/coin/{id}', [App\Http\Controllers\Api\V1\WalletController::class, 'updateCoinvalue']);  //route for updating unit coin values
Route::post('/add-wallet/user/{id}/{wallet_id}', [App\Http\Controllers\Api\V1\WalletController::class, 'userAddwallet']); //route for adding wallet for a specific user 
Route::patch('/wallet-balance/update/{id}/{wallet_id}', [App\Http\Controllers\Api\V1\WalletController::class, 'updateWalletBalance']); //route for updating wallet balance
Route::delete('/delete/wallet/{id}', [App\Http\Controllers\Api\V1\WalletController::class, 'delete']); //route for deleting a wallet
Route::get('/wallets', [App\Http\Controllers\Api\V1\WalletController::class, 'index']); //route for showing all the wallets

Route::post('/loan-insurence/request/{id}', [App\Http\Controllers\Api\V1\LoanController::class, 'createRequest']); //route for adding loan request
Route::patch('/update/request-status/{id}/{user_id}', [App\Http\Controllers\Api\V1\LoanController::class, 'updateRequestStatus']); //route for adding accept or reject loan request
Route::delete('/request/delete/{id}', [App\Http\Controllers\Api\V1\LoanController::class, 'deleteRequest']); //route for deleting loan request
