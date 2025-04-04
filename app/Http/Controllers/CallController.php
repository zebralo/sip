<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CallController extends Controller
{
    public function handleIncomingCall(Request $request)
    {
        $phone = $request->input('phone');

        // سجل الرقم في اللوج
        Log::info('📞 Incoming call received', [
            'phone' => $phone,
        ]);

        // رجّع ريسبونس بسيط لتأكيد الاستلام
        return response()->json([
            'status' => 'test_mode',
            'message' => 'Call received and logged.',
            'phone' => $phone
        ]);
    }
}
