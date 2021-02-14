<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ServiceProvider;

class OrderController extends Controller
{
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
        $validateId = Order::idRules($id);
        if($validateId) {
            return $validateId;
        }

        return Order::find($id);

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
        $validator = Validator::make($request->all(), Order::$createRules); 

        if($validator->fails()) {
          return $validator->errors();
        }

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

        //Validate body
        $validator = Validator::make($request->all(), Order::$updateRules); 
        if($validator->fails()) {
          return $validator->errors();
        }

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

        //Validate body
        $validator = Validator::make($request->all(), Order::$addRules); 
        if($validator->fails()) {
            return $validator->errors();
        }

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

        //Validate body
        $validator = Validator::make($request->all(), Order::$payRules); 
        if($validator->fails()) {
            return $validator->errors();
        }

        //Check order has payed
        $order = Order::find($id);
        if($order->payed) {
            return response()->json([
                'message' => 'Can not pay a payed order!'
            ],422);
        }

        try {

            DB::beginTransaction();

            $servideProvider = ServideProvider::find($request->service_provider_id);

            $response = Http::post($serviceProvider->url, [
                'order_id' => 'Steve',
                'email' => 'Steve',
                'value' => 'Network Administrator'
            ]);

            if($response->message == 'Payment Successful') {
                $order->update(['payed',true]);
            }

            DB::commit();

        } catch(Exception $e) {
            DB::rollback();
            report($e);
        }

        return response()->json(['success' => true],200);

    }

}
