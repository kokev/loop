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

    /**
     * Selected payment provider all details.
     * 
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request, $id)
    {
        $validateId = PaymentProvider::idRules($id);
        if($validateId) {
            return $validateId;
        }

        return PaymentProvider::find($id);

    }
}
