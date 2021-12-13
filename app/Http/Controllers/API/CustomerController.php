<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Validator;
use Hash;
use DateTime;
use App\Http\Resources\Customer as CustomerResource;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:customer')->except('logout');
    }
    /**
     * Customer Register
     */
    public function register(Request $request)
    {
        // validate
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'type' => 'required'
        ]);
        $customer = Customer::where("email", $request->email)->get();
        if ($validator->fails() || count($customer) > 0) {
            // return response
            $response = [
                'success' => false,
                'token' => ''
            ];
            return response()->json($response, 200);
        }

        // insert to DB
        $input = $request->all();
        $current_date = Carbon::now();
        $input['password'] = bcrypt($input['password']);
        $input['purchase_time'] = $current_date->toDateTimeString();
        $Customer = Customer::create($input);
        $accessToken = $Customer->createToken(config('app.name'))->accessToken;

        // return response
        $response = [
            'success' => true,
            'token' => $accessToken,
            'id' => $Customer->id
        ];
        return response()->json($response, 200);
    }

    /**
     * Customer Login
     */
    public function login(Request $request)
    {
        if (Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $Customer = Auth::guard('customer')->user();
            $accessToken = $Customer->createToken(config('app.name'))->accessToken;
            // return response
            $response = [
                'success' => true,
                'token' => $accessToken,
                'id' => $Customer->id
            ];
            return response()->json($response, 200);
        } else {
            // return response
            $response = [
                'success' => false,
                'token' => ''
            ];
            return response()->json($response, 404);
        }
    }

    public function profile(Request $request)
    {
        return CustomerResource::make($request->user());
    }

    public function checkAvailable(Request $request)
    {
        $user = $request->user();
        $available = true;
        $current_datetime = Carbon::now()->timestamp;

        if ($user->type == 0) {
            $date = new DateTime($user->purchase_time);
            $limit_date = $date->modify('+1 day');
            $purchase_datetime = strtotime($limit_date->format('Y-m-d H:i:s'));
            $diff = $current_datetime - $purchase_datetime;

            if ($diff > 0) {
                $available = false;
            } else {
                $available = true;
            }
        } else {
            $date = new DateTime($user->purchase_time);
            $limit_date = $date->modify('+1 month');
            $purchase_datetime = strtotime($limit_date->format('Y-m-d H:i:s'));
            $diff = $current_datetime - $purchase_datetime;

            if ($diff > 0) {
                $available = false;
            } else {
                $available = true;
            }
        }

        return response()->json($available, 200);
    }

    public function purchaseMembership(Request $request)
    {
        $user = $request->user();
        $current_date = Carbon::now();
        $user['purchase_time'] = $current_date->toDateTimeString();
        $user['type'] = $request->type;
        $user->save();
        return response()->json(true, 200);

    }
}
