<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\UserHasWallet;
use App\Models\User;
use Validator;
use Response;
use Auth;
use DB;

class WalletController extends Controller
{
    public function index()
    {
        $wallet = Wallet::all();

        if (count($wallet)<1) {

            return response()->json([
                "status" => "404 Not Found",
            ]);
        } else {
            return response()->json([
                "success" => true,
                "total"=>count($wallet),
                "message" => "wallets retrieved successfully.",
                "data" => $wallet
            ]);
        }
        
    }
    
    public function create(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'wallet_no' => 'required',
            'unit_coin_value' => 'required',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
              'errors' => $validator->errors(),
              'status' => 'Validation failed',
            ]);
        }
        
        $wallet = Wallet::create($input);
        return response()->json([
            "success" => true,
            "message" => "Wallet created successfully.",
            "data" => $wallet
        ]);
    }

    public function updateCoinvalue(Request $request, $id)
    {
        $wallet = Wallet::findOrFail($id);
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'unit_coin_value' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
              'errors' => $validator->errors(),
              'status' => 'Validation failed',
            ]);
        }

        Wallet::where('id',$id)->update($input);
        
        return response()->json([
            "status" => "200 Ok",
            "message" => "Coin value updated for wallet no."." ".$wallet->wallet_no,       
        ]);


    }

    public function userAddwallet($id, $wallet_id)
    {
        $user = User::findOrfail($id);
        $wallet = Wallet::findOrfail($wallet_id);
        $wallet_balance = $wallet->unit_coin_value;
        $coins = $wallet_balance/$wallet->unit_coin_value;
        $input = array(
            'wallet_id' => $wallet->id,
            'user_id' => $user->id,
            'wallet_balance' =>  $wallet_balance,
            'coins' =>$coins,
            'unit_coin_value' =>  $wallet->unit_coin_value,
        );
        $validator = Validator::make($input, [
            'wallet_id' => 'required',
            'wallet_balance' => 'required',
            'user_id' => 'required',
            'coins' => 'required',
            'unit_coin_value' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
              'errors' => $validator->errors(),
              'status' => 'Validation failed',
            ]);
        }
        
        $wallet = UserHasWallet::create($input);
        return response()->json([
            "success" => true,
            "message" => "Wallet added to user successfully.",
            "data" => $input
        ]);

    }

    public function updateWalletBalance(Request $request, $id, $wallet_id)
    {
        $user = User::findOrfail($id);
        $wallet = Wallet::findOrfail($wallet_id);
        $user_wallet = UserHasWallet::where('wallet_id',$wallet->id)->where('user_id',$user->id)->first();
        $wallet_balance = $user_wallet->wallet_balance + $request->wallet_balance;
        $coins = $wallet_balance/$wallet->unit_coin_value;
        
        $input = array(
            
            'wallet_id' => $wallet->id,
            'user_id' => $user->id,
            'wallet_balance' =>  $wallet_balance,
            'coins' =>$coins,
            'unit_coin_value' =>  $wallet->unit_coin_value,
        );
        
             
        $validator = Validator::make($input, [
            'wallet_balance' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
              'errors' => $validator->errors(),
              'status' => 'Validation failed',
            ]);
        }

        UserHasWallet::where('user_id',$id)->where('wallet_id',$wallet_id)->update($input);
        
        return response()->json([
            "status" => "200 Ok",
            "message" => "Wallet Balance updated for wallet no."." ".$wallet->wallet_no,       
        ]);

    }


    public function delete($id)
    {
        $wallet = Wallet::findOrFail($id);
        if ($wallet) {
            $wallet->delete();
            return response()->json([
                "success" => true,
                "message" => "Wallet deleted successfully.",

            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong!",
                
            ]);
        }
    }


}
