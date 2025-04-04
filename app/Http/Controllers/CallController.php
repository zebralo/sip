<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CallController extends Controller
{
    public function handleIncomingCall(Request $request)
    {
        $phone = $request->input('phone'); // الرقم اللي جاي من الـPBX

        $customer = Customer::where('phone', 'like', '%' . $phone . '%')->first();

        if ($customer) {
            // عميل موجود
            $customer->phone = $phone;
            $customer->count_updates = $customer->count_updates+1;
            $customer->save();

            return response()->json([
                'status' => 'found',
                'customer' => $customer
            ]);
        } else {
            // عميل جديد
            $customer = new Customer();
            $customer->name = $phone;
            $customer->phone = $phone;
            $customer->save();
            
            return response()->json([
                'status' => 'not_found',
                'message' => 'Customer not found.'
            ]);
        }
    }
}
