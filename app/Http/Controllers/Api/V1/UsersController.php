<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserHasWallet;
use App\Models\Wallet;
use App\Models\UserService;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Validator;
use Response;
use Auth;
use DB;

class UsersController extends Controller
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
     * Create a new user.
     * Method: POST
     * 
     */
    public function createUser(Request $request){

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'adhar_pan' => 'required',
            'annual_income' => 'required',
            'password' => 'required|min:3',
            'confirm_password' => 'required|same:password',
            'dob' => 'required|date',
            'address' => 'required',
            'agent'=>['required',Rule::in(["Yes", "No"])],
        ]);
        //print_r($input);die;
        if ($validator->fails()) {
            return response()->json([
              'errors' => $validator->errors(),
              'status' => 'Validation failed',
            ]);
        }
        $input['password']=Hash::make($input['password']);
        $user = User::create($input);
        return response()->json([
            "success" => true,
            "message" => "User created successfully.",
            "data" => $user,
            
        ]);
    }

    /**
     * Get List of All Users.
     * Method: GET
     * 
     */
    public function listUsers(){

        $users = User::all();
        
        return response()->json([
            "success" => true,
            "message" => "Users retrieved successfully.",
            "data" => $users,
            "total" =>count($users),
        ]);
    }


    public function UserLogin(Request $request){

        $input = $request->all();

        $validator = Validator::make($input, [
            'email' => 'required|email',
            'password' => 'required',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
              'errors' => $validator->errors(),
              'status' => 'Validation failed',
            ]);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            ///$accessToken = $user->createToken('authToken')->accessToken;
            return response()->json([
                'success' => true,
                'status' => 'User Login Successfull.',
                
              ]);
        } 
        else{ 
            return response()->json([
                'success' => false,
                'status' => 'Invalid username or password',
              ]);
        } 
    }


   


    
}
