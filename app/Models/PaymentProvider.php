<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentProvider extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'url'
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
     * Validation for payment provider  id
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
                'id' => 'required|integer|exists:payment_providers,id',
            ],
            [
                'id.exists' => 'Payment provider doesnt exist'
            ]
        );
    
        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    }
}
