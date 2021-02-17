<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PaymentProvider;
use App\Http\Resources\OrderViewResource;
use App\Http\Traits\OrderTrait;

class OrderController extends Controller
{
    use OrderTrait;

    /**
     * List of orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Order::all();
    }

    /**
     * Selected order all details.
     * 
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request, $id)
    {
        //Validate id
        $validateId = Order::idRules($id);
        if($validateId) {
            return $validateId;
        }

        OrderViewResource::withoutWrapping();

        return new OrderViewResource(Order::where('id',$id)->with(['customer'])->first());

    }

    /**
     * Create an order.
     * 
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //Validate request body
        $request->validate(Order::$createRules);

        try {

            DB::beginTransaction();

            $order = new Order;
            $order->customer_id = $request->customer_id;
            $order->payed = false;
            $order->save();

            DB::commit();

        } catch(Exception $e) {
            DB::rollback();
            report($e);
            return response()->json([
                'message' => 'Failed to create new order'
            ],422);
        }

        return response()->json(['success' => true],200);
    }

    /**
     * Update a selected order.
     * 
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Validate id
        $validateId = Order::idRules($id);
        if($validateId) {
            return $validateId;
        }

        //Validate request body
        $request->validate(Order::$updateRules);

        try {

            DB::beginTransaction();

            $order = Order::find($id);
            $order->customer_id = $request->customer_id;
            $order->payed = $request->payed;
            $order->save();

            DB::commit();

        } catch(Exception $e) {
            DB::rollback();
            report($e);
            return response()->json([
                'message' => 'Failed to update order'
            ],422);
        }

        return response()->json(['success' => true],200);

    }

    /**
     * Delete a selected order.
     * 
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        $validateId = Order::idRules($id);
        if($validateId) {
            return $validateId;
        }

        //Check order has payed
        $order = Order::find($id);
        if($order->payed) {
            return response()->json([
                'message' => 'Can not delete payed order!'
            ],422);
        }

        try {

            DB::beginTransaction();

            $order->delete();

            DB::commit();

        } catch(Exception $e) {
            DB::rollback();
            report($e);
            return response()->json([
                'message' => 'Failed to delete order'
            ],422);
        }

        return response()->json(['success' => true],200);

    }

    /**
     * Add product to an order.
     * 
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request, $id)
    {
        //Validate id
        $validateId = Order::idRules($id);
        if($validateId) {
            return $validateId;
        }

        //Validate request body
        $request->validate(Order::$addRules);

        //Check order has payed
        $order = Order::find($id);
        if($order->payed) {
            return response()->json([
                'message' => 'Can not add product to a payed order!'
            ],422);
        }

        try {

            DB::beginTransaction();

            $orderDetail = new OrderDetail;
            $orderDetail->order_id = $id;
            $orderDetail->product_id = $request->product_id;
            $orderDetail->save();

            DB::commit();

        } catch(Exception $e) {
            DB::rollback();
            report($e);
            return response()->json([
                'message' => 'Failed to add product to an order'
            ],422);
        }

        return response()->json(['success' => true],200);

    }

    /**
     * Pay an order.
     * 
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function pay(Request $request, $id)
    {
        //Validate id
        $validateId = Order::idRules($id);
        if($validateId) {
            return $validateId;
        }

        //Validate request body
        $request->validate(Order::$payRules);

        //Check order has payed
        $order = Order::where('id',$id)->with(['customer'])->first();
        if($order->payed) {
            return response()->json([
                'message' => 'Can not pay a payed order!'
            ],422);
        }

        //Calculate the price of the order
        $value = $this->calculatePrice($id);

        $success = false;
        try {

            DB::beginTransaction();

            $paymentProvider = PaymentProvider::find($request->payment_provider_id);

            $response = Http::post($paymentProvider->url, [
                'order_id' => $id,
                'customer_email' => $order->customer->email_address,
                'value' => $value
            ]);

            if($response->status() == 200) {
                $order->update(['payed' => true]);
                $success = true;
            } else {
                Log::info('PAYMENT_RPOVIDER_RESPONSE: '.$response);
            }

            DB::commit();

        } catch(Exception $e) {
            DB::rollback();
            report($e);
            return response()->json([
                'message' => 'Payment failed!'
            ],422);
        }

        if($success) {
            return response()->json(['success' => true],200);
        } else {
            return response()->json([
                'message' => 'Something went wrong during the payment!'
            ],422);
        }

    }

}
