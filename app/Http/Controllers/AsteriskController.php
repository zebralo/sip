<?php

namespace App\Http\Controllers;

use App\Services\AsteriskService;
use Illuminate\Http\Request;

class AsteriskController extends Controller
{
    protected $asterisk;

    public function __construct(AsteriskService $asterisk)
    {
        $this->asterisk = $asterisk;
    }

    public function testAmi()
    {
        $response = $this->asterisk->testConnection();
        return response()->json($response);
    }

    public function originateCall(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $response = $this->asterisk->originateCall($from, $to);
        return response()->json($response);
    }
}
