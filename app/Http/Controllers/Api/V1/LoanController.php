<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Insurance;
use App\Models\Loan;
use App\Models\LoanOrInsurenceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Response;
use Validator;

class LoanController extends Controller
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
     * Get all loans.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loan = Loan::all();

        return response()->json([
            "success" => true,
            "total" => count($loan),
            "message" => "Loans retrieved successfully.",
            "data" => $loan,

        ]);
    }

    /**
     * Create a new loan.
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
            'rate_of_interest' => 'required',
            'duration' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => 'Validation failed',
            ]);
        }

        $loan = Loan::create($input);
        return response()->json([
            "success" => true,
            "message" => "Loan added successfully.",
            "data" => $loan,
        ]);
    }

    /**
     * Update a loan.
     * Method: PATCH
     *
     */
    public function update($id, Request $request)
    {

        $loan = Loan::findOrFail($id);
        $input = $request->all();

        $validator = Validator::make($input, [
            'amount' => 'required',
            'type' => 'required',
            'agent_name' => 'required',
            'rate_of_interest' => 'required',
            'duration' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => 'Validation failed',
            ]);
        }

        Loan::where('id', $id)->update($input);

        return response()->json([
            "success" => true,
            "message" => "Loan updated successfully.",

        ]);
    }

    /*
     * Delete a loan
     */

    public function delete($id)
    {
        $loan = Loan::findOrFail($id);
        if ($loan) {
            $loan->delete();
            return response()->json([
                "success" => true,
                "message" => "Loan record delete successfully.",

            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "There is some error",

            ]);
        }

    }

    public function createRequest($id, Request $request)
    {
        $user = User::findOrfail($id);

        if ($request->request_type == "loan") {

            $loan = Loan::find($request->request_type_id);
            $request_details = Loan::where('id', $request->request_type_id)->first();
            if (empty($loan)) {
                $data['status'] = "Loan Not Found!";
                return $data;
            } else {
                $input = array(
                    'request_type_id' => $loan->id,
                    'user_id' => $user->id,
                    'request_type' => $request->request_type,
                );
            }
        } else {

            $insurance = Insurance::find($request->request_type_id);
            $request_details = Insurance::where('id', $request->request_type_id)->first();
            if (empty($insurance)) {
                $data['status'] = "Insurance Not Found!";
                return $data;
            } else {
                $input = array(
                    'request_type_id' => $request->request_type_id,
                    'user_id' => $user->id,
                    'request_type' => $request->request_type,
                );
            }

        }

        $validator = Validator::make($input, [
            'request_type_id' => 'required',
            'request_type' => 'required', //determines which type  loan or insurence
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => 'Validation failed',
            ]);
        }

        $loan_or_ins_req = LoanOrInsurenceRequest::create($input);
        return response()->json([
            "status" => "200 Ok",
            "message" => "Request sent successfully.",
            "request details" => $request_details,
        ]);
    }

    public function updateRequestStatus(Request $request, $id, $user_id)
    {
        $user = User::findOrfail($user_id);

        if ($user->agent == "Yes") {

            $request_type = LoanOrInsurenceRequest::findOrfail($id);

            $input = array(
                'status' => $request->status,
            );

            $validator = Validator::make($input, [
                'status' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'status' => 'Validation failed',
                ]);
            }

            if (empty($request_type)) {
                $data['status'] = "404 Loan or Insurence Request Not Found";
                return $data;
            } else {

                $update_request = LoanOrInsurenceRequest::where('id', $request_type->id)->update($input);

                if ($request_type->request_type == "loan") {
                   $details = Loan::where('id',$request_type->request_type_id)->first();
                } else {
                   $details = Insurance::where('id',$request_type->request_type_id)->first(); 
                }
                
                 
                if ($request->status == 0) {
                    $update_status = "Rejected";
                    return response()->json([
                        "success" => true,
                        "message" => "Request is" . " " . $update_status,
                        "data" => $request_type,
                        "request details" => $details,
                    ]);
                } else {
                    $update_status = "Accepted";
                    return response()->json([
                        "success" => true,
                        "message" => "Request is" . " " . $update_status,
                        "data" => $request_type,
                        "request details" => $details,
                    ]);
                }

            }

        } else {

            $data['msg'] = "Unauthorized!..You Are Not an Agent";
            return $data;
        }

    }

    public function deleteRequest($id)
    {
        $request = LoanOrInsurenceRequest::findOrFail($id);
        if ($request) {
            $request->delete();
            return response()->json([
                "success" => true,
                "message" => "Request deleted successfully.",

            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong!",
                
            ]);
        }

    }
}
