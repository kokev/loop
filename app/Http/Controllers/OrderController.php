<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * List of orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

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

    }

    /**
     * Add order.
     * 
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Update a selected order.
     * 
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

    }

    /**
     * Delete a selected order.
     * 
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {

    }

    /**
     * Add product to an order.
     * 
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {

    }

    /**
     * Pay an order.
     * 
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function pay(Request $request)
    {

    }
}
