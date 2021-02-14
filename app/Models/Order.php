<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'customer_id',
        'payed'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Validation for order id
     *
     * @param int $id
     * 
     * @return Json|boolean
     */
    public static function idRules($id) {
        $validator = Validator::make(
            [
                'id' => $id,
            ],
            [
                'id' => 'required|integer|exists:orders,id',
            ],
            [
                'id.exists' => 'Order doesnt exist'
            ]
        );
    
        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    }

    /**
     * Validation rules when create a new order (payed default false)
     *
     * @var array
     */
    public static $createRules = [
        'customer_id' => 'required|integer|exists:customers,id'
    ];
    
    /**
     * Validation rules when update an exists order
     *
     * @var array
     */
    public static $updateRules = [
        'customer_id' => 'required|integer|exists:customers,id',
        'payed' => 'required|boolean',
    ];

    /**
     * Validation rules when add products to an exist order
     *
     * @var array
     */
    public static $addRules = [
        'product_id' => 'required|integer|exists:products,id'
    ];

    /**
     * Validation rules when add products to an exist order
     *
     * @var array
     */
    public static $payRules = [
        'service_provider_id' => 'required|integer|exists:service_provider,id'
    ];
}
