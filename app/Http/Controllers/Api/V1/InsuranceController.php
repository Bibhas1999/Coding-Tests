<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Insurance;
use Validator;
use Response;

class InsuranceController extends Controller
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
     * Get all insurance.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $insurance = Insurance::all();

        return response()->json([
            "success" => true,
            "total"=>count($insurance),
            "message" => "Insurances retrieved successfully.",
            "data" => $insurance
        ]);
    }

    /**
     * Create a new insurance.
     * Method: POST
     * 
     */
    public function create(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'amount' => 'required',
            'type' => 'required',
            'agent_name' => 'required',
            'duration' => 'required',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
              'errors' => $validator->errors(),
              'status' => 'Validation failed',
            ]);
        }
        
        $insurance = Insurance::create($input);
        return response()->json([
            "success" => true,
            "message" => "Insurance added successfully.",
            "data" => $insurance
        ]);
    }

    /**
     * Update a insurance.
     * Method: PATCH
     * 
     */
    public function update($id,Request $request)
    {
        
        $insurance = Insurance::findOrFail($id);
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'amount' => 'required',
            'type' => 'required',
            'agent_name' => 'required',
            'duration' => 'required',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
              'errors' => $validator->errors(),
              'status' => 'Validation failed',
            ]);
        }

             
        $insurance=Insurance::where('id',$id)->update($input);

        
        return response()->json([
            "success" => true,
            "message" => "Insurance updated successfully.",
            
           
        ]);
    }

    /*
    * Delete a insyrance
    */

    public function delete($id){
        $insurance = Insurance::findOrFail($id);
        if($insurance) {
            $insurance->delete(); 
            return response()->json([
                "success" => true,
                "message" => "Insurance record delete successfully.",
               
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
