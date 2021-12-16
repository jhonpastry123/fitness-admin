<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CustomerInformation;
use Illuminate\Http\Request;

use Validator;
use Carbon\Carbon;
use App\Http\Resources\CustomerInformation as CustomerInformationResource;
use App\Http\Resources\GraphValue as GraphValueResource;

class CustomerInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'customer_id' => 'required',
            'goal' => 'required',
            'initial_weight' => 'required',
            'weight' => 'required',
            'gender' => 'required',
            'height' => 'required',
            'birthday' => 'required',
            'gym_type' => 'required',
            'sport_type1' => 'required',
            'sport_type2' => 'required',
            'sport_type3' => 'required',
            'sport_time1' => 'required',
            'sport_time2' => 'required',
            'sport_time3' => 'required',
            'goal_weight' => 'required',
            'weekly_goal' => 'required',
            'diet_mode' => 'required',
        ]);

        if ($validator->fails()) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
            ];
            return response()->json(false, 404);
        }

        $current_date = Carbon::now();
        $input['date'] = $current_date->toDateString();

        $information = CustomerInformation::updateOrCreate([
            "customer_id" => $input['customer_id'],
            "date" => $input['date'],
        ],$input);

        // return response
        // $response = [
        //     'success' => true,
        //     'message' => 'Customer Information created successfully.',
        // ];

        return response()->json(true, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerInformation  $customerInformation
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        dd($request);
    }

    public function getInformation(Request $request)
    {
        $input = $request->all();
        $customer = $request->user();
        $information = CustomerInformation::where('customer_id', $customer->id)->where('date', '<=', $input['date'])->orderBy('date', 'DESC')->latest()->first();
        if ($information->date != $input['date']) {
            $information['water'] = 0;
        }
        
        return CustomerInformationResource::make($information);;
    }

    public function getGraphValues(Request $request)
    {
        $input = $request->all();
        $customer = $request->user();
        $informations = CustomerInformation::select(['id', 'date', 'weight'])->where('customer_id', $customer->id)->where('date', '<=', $input['date'])->orderBy('date', 'ASC')->latest()->get();
        
        return GraphValueResource::collection($informations);
    }

    public function saveDietMode(Request $request)
    {
        $dietmode = $request->get('diet_mode');
        $customer = $request->user();
        $current_date = Carbon::now();
        $date = $current_date->toDateString();

        $information = CustomerInformation::where('customer_id', $customer->id)->where('date', '=', $date)->orderBy('date', 'DESC')->latest()->first();
        if ($information) {
            $information->diet_mode = $dietmode;
            $information->save();
        } else {
            $information = CustomerInformation::where('customer_id', $customer->id)->where('date', '<=', $date)->orderBy('date', 'DESC')->latest()->first();
            $new_info = $information->toArray();
            $new_info['date'] = $date;
            $new_info['diet_mode'] = $dietmode;
            $new_info['water'] = 0;
            CustomerInformation::create($new_info);
        }
        
        return response()->json(true, 200);
    }

    public function saveWeight(Request $request)
    {
        $weight = $request->get('weight');
        $customer = $request->user();
        $current_date = Carbon::now();
        $date = $current_date->toDateString();

        $information = CustomerInformation::where('customer_id', $customer->id)->where('date', '=', $date)->orderBy('date', 'DESC')->latest()->first();
        if ($information) {
            $information->weight = $weight;
            $information->save();
        } else {
            $information = CustomerInformation::where('customer_id', $customer->id)->where('date', '<=', $date)->orderBy('date', 'DESC')->latest()->first();
            $new_info = $information->toArray();
            $new_info['date'] = $date;
            $new_info['weight'] = $weight;
            $new_info['water'] = 0;
            CustomerInformation::create($new_info);
        }
        
        return response()->json(true, 200);
    }

    public function saveWater(Request $request)
    {
        $water = $request->get('water');
        $customer = $request->user();
        $current_date = Carbon::now();
        $date = $current_date->toDateString();

        $information = CustomerInformation::where('customer_id', $customer->id)->where('date', '=', $date)->orderBy('date', 'DESC')->latest()->first();
        if ($information) {
            $information->water = $water;
            $information->save();
        } else {
            $information = CustomerInformation::where('customer_id', $customer->id)->where('date', '<=', $date)->orderBy('date', 'DESC')->latest()->first();
            $new_info = $information->toArray();
            $new_info['date'] = $date;
            $new_info['water'] = $water;
            CustomerInformation::create($new_info);
        }
        
        return response()->json(true, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerInformation  $customerInformation
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerInformation $customerInformation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerInformation  $customerInformation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerInformation $customerInformation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerInformation  $customerInformation
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerInformation $customerInformation)
    {
        //
    }
}
