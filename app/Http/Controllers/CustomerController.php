<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $customers = Customer::latest()->paginate(5);

        foreach ($customers as $key => $customer) {
            $available = true;
            $current_datetime = Carbon::now()->timestamp;

            if ($customer->type == 0) {
                $date = new DateTime($customer->purchase_time);
                $limit_date = $date->modify('+1 day');
                $purchase_datetime = strtotime($limit_date->format('Y-m-d H:i:s'));
                $diff = $current_datetime - $purchase_datetime;

                if ($diff > 0) {
                    $available = false;
                } else {
                    $available = true;
                }
            } else {
                $date = new DateTime($customer->purchase_time);
                $limit_date = $date->modify('+1 month');
                $purchase_datetime = strtotime($limit_date->format('Y-m-d H:i:s'));
                $diff = $current_datetime - $purchase_datetime;

                if ($diff > 0) {
                    $available = false;
                } else {
                    $available = true;
                }
            }

            $customer['available'] = $available;
            switch ($customer->type) {
                case 0:
                    $customer['membership'] = "Free";
                    break;
                case 1:
                    $customer['membership'] = "Bronze";
                    break;
                case 2:
                    $customer['membership'] = "Gold";
                    break;
                default:
                    $customer['membership'] = "Free";
                    break;
            } 
        }
        

        return view('customers.index', compact('customers'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
        $customer->delete();

        return  redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully');
    }
}
