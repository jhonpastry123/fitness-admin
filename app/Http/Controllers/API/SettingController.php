<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Setting;
use Validator;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        // $settings = Setting::all();
        $settings = Setting::where('user_id', $request->user_id)->where('date', $request->date)->get();
        $response = [
            'success' => true,
            'message' => 'Settings retrieved successfully.',
            'settings' => $settings
        ];

        return response()->json($response, 200);
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
            'user_id' => 'required',
            'date' => 'required',
            'exercise_rate' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'neck' => 'required',
            'waist' => 'required',
            'thigh' => 'required',
            'weekly_goal' => 'required',
        ]);

        if ($validator->fails()) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
            ];
            return response()->json($response, 404);
        }

        $setting = Setting::where("user_id", $request->user_id)->where("date", $request->date)->get();

        if (count($setting) > 0) {
            Setting::where("user_id", $request->user_id)->where("date", $request->date)
                ->update([
                    'user_id' => $request->user_id,
                    'date' => $request->date,
                    'exercise_rate' => $request->exercise_rate,
                    'sport1_type' => $request->sport1_type ? $request->sport1_type : 0,
                    'sport1_time' => $request->sport1_time ? $request->sport1_time : 0,
                    'sport2_type' => $request->sport2_type ? $request->sport2_type : 0,
                    'sport2_time' => $request->sport2_time ? $request->sport2_time : 0,
                    'sport3_type' => $request->sport3_type ? $request->sport3_type : 0,
                    'sport3_time' => $request->sport3_time ? $request->sport3_time : 0,
                    'height' => $request->height,
                    'weight' => $request->weight,
                    'neck' => $request->neck,
                    'waist' => $request->waist,
                    'thigh' => $request->thigh,
                    'weekly_goal' => $request->weekly_goal
                ]);
            // return response
            $response = [
                'success' => true,
                'message' => 'Setting updated successfully.',
            ];
        } else {

            Setting::create([
                'user_id' => $request->user_id,
                'date' => $request->date,
                'exercise_rate' => $request->exercise_rate,
                'sport1_type' => $request->sport1_type ? $request->sport1_type : 0,
                'sport1_time' => $request->sport1_time ? $request->sport1_time : 0,
                'sport2_type' => $request->sport2_type ? $request->sport2_type : 0,
                'sport2_time' => $request->sport2_time ? $request->sport2_time : 0,
                'sport3_type' => $request->sport3_type ? $request->sport3_type : 0,
                'sport3_time' => $request->sport3_time ? $request->sport3_time : 0,
                'height' => $request->height,
                'weight' => $request->weight,
                'neck' => $request->neck,
                'waist' => $request->waist,
                'thigh' => $request->thigh,
                'weekly_goal' => $request->weekly_goal
            ]);

            // return response
            $response = [
                'success' => true,
                'message' => 'Setting created successfully.',
            ];
        }


        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        // return response
        $response = [
            'success' => true,
            'message' => 'Recipe retrieved successfully.',
            'setting' => $setting
        ];

        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_id' => 'required',
            'date' => 'required',
            'exercise_rate' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'neck' => 'required',
            'waist' => 'required',
            'thigh' => 'required',
            'weekly_goal' => 'required',
        ]);

        if ($validator->fails()) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
            ];
            return response()->json($response, 404);
        }

        $setting->update([
            'user_id' => $request->user_id,
            'date' => $request->date,
            'exercise_rate' => $request->exercise_rate,
            'sport1_type' => $request->sport1_type ? $request->sport1_type : 0,
            'sport1_time' => $request->sport1_time ? $request->sport1_time : 0,
            'sport2_type' => $request->sport2_type ? $request->sport2_type : 0,
            'sport2_time' => $request->sport2_time ? $request->sport2_time : 0,
            'sport3_type' => $request->sport3_type ? $request->sport3_type : 0,
            'sport3_time' => $request->sport3_time ? $request->sport3_time : 0,
            'height' => $request->height,
            'weight' => $request->weight,
            'neck' => $request->neck,
            'waist' => $request->waist,
            'thigh' => $request->thigh,
            'weekly_goal' => $request->weekly_goal
        ]);

        // return response
        $response = [
            'success' => true,
            'message' => 'Setting updated successfully.',
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        $setting->delete();

        // return response
        $response = [
            'success' => true,
            'message' => 'Setting deleted successfully.',
        ];

        return response()->json($response, 200);
    }
}
