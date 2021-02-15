<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentProvider;

class PaymentProviderController extends Controller
{
    /**
     * List of payment providers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return PaymentProvider::all();
    }
}
