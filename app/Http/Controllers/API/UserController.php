<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Validator;
use Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:customer')->except('logout');
    }
    /**
     * User Register
     */
    public function register(Request $request)
    {
        // validate
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'gender' => 'required',
            'birthday' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
            ];
            return response()->json($response, 404);
        }

        // insert to DB
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = Customer::create($input);
        $accessToken = $user->createToken('ApplicationName')->accessToken;

        // return response
        $response = [
            'success' => true,
            'message' => 'User registration successful',
            'accessToken' => $accessToken,
        ];
        return response()->json($response, 200);
    }

    /**
     * User Login
     */
    public function login(Request $request)
    {
        if (Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::guard('customer')->user();
            $accessToken = $user->createToken('ApplicationName')->accessToken;
            // return response
            $response = [
                'success' => true,
                'message' => 'User login successful',
                'accessToken' => $accessToken,
                'user_id' => $user->id
            ];
            return response()->json($response, 200);
        } else {
            // return response
            $response = [
                'success' => false,
                'message' => 'Unauthorised',
            ];
            return response()->json($response, 404);
        }
    }
}
