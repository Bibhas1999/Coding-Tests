<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\UserHasWallet;
use Validator;
use Response;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }
    
    /**
     * Get all transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaction = Transaction::all();

        return response()->json([
            "success" => true,
            "total"=>count($transaction),
            "message" => "Transactions retrieved successfully.",
            "data" => $transaction
        ]);
    }

    /**
     * Create a new insurance.
     * Method: POST
     * 
     */
    public function create(Request $request)
    {
        
        $from_wallet = UserHasWallet::where('id',$request->from_user_wallet_id)->first();
        $to_wallet = UserHasWallet::where('id',$request->to_user_wallet_id)->first();
        
        if (empty($from_wallet) or empty($to_wallet)) {

            $data['status'] = "404 User Wallet Not Found!";
            return $data;

        } else {
            
            $time = date("H:i:s");
            $input = array(
                'amount' => $request->amount,
                'time' => $time,
                'from_user_wallet_id' => $from_wallet->id,
                'to_user_wallet_id' => $to_wallet->id,
            );
            $validator = Validator::make($input, [
                'amount' => 'required',
                'from_user_wallet_id' => 'required',
                'to_user_wallet_id' => 'required',
                
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                  'errors' => $validator->errors(),
                  'status' => 'Validation failed',
                ]);
            }
            
            if($from_wallet->wallet_balance<=$from_wallet->unit_coin_value or $from_wallet->wallet_balance<$request->amount){
                 
                $data['status'] = "Transaction Failed";
                $data['message'] = "Insufficient Wallet Balance";
                return $data;

            }else {
                
                $transaction = Transaction::create($input);

                $current_balance_from_wallet = $from_wallet->wallet_balance - $request->amount; //for sender wallet
                $current_coins_from_wallet = $current_balance_from_wallet/$from_wallet->unit_coin_value; //for sender wallet

                $current_balance_to_wallet = $to_wallet->wallet_balance + $request->amount; //for reciever wallet
                $current_coins_to_wallet = $current_balance_to_wallet/$to_wallet->unit_coin_value; //for reciever wallet
                
                $deduction = array(
                    'wallet_balance' => $current_balance_from_wallet,
                    'coins' => $current_coins_from_wallet,
                );
                
                $addition = array(
                    'wallet_balance' => $current_balance_to_wallet,
                    'coins' => $current_coins_to_wallet,
                );
                 
                $wallet_deduc_update = UserHasWallet::where('id',$from_wallet->id)->update($deduction); 
                $wallet_add_update = UserHasWallet::where('id',$to_wallet->id)->update($addition); 
                

                return response()->json([
                    "success" => true,
                    "message" => "Transaction added successfully.",
                    "data" => $transaction
                ]);
            }
            
        }
        
       
    }

    /**
     * Update a insurance.
     * Method: PATCH
     * 
     */
    public function update($id,Request $request)
    {
        
        $transaction = Transaction::findOrFail($id);
        //print_r($transaction);
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'amount' => 'required',
            'time' => 'required',
            'from_account' => 'required',
            'to_account' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
              'errors' => $validator->errors(),
              'status' => 'Validation failed',
            ]);
        }

             
        Transaction::where('id',$id)->update($input);

        
        return response()->json([
            "success" => true,
            "message" => "Transaction updated successfully.",
                      
        ]);
    }

    /*
    * Delete a insyrance
    */

    public function delete($id){
        $transaction = Transaction::findOrFail($id);
        if($transaction) {
            $transaction->delete(); 
            return response()->json([
                "success" => true,
                "message" => "Transaction record delete successfully.",
               
            ]);
        } 
        
        else {
            return response()->json([
                "success" => false,
                "message" => "There is some error",
               
            ]);
        }
        
    }
}
