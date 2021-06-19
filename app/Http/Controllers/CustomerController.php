<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

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
