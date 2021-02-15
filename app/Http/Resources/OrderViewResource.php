<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrderDetailResource;
use App\Models\OrderDetail;

use App\Http\Traits\OrderTrait;

class OrderViewResource extends JsonResource
{
    use OrderTrait;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $value = $this->calculatePrice($this->id);
        
        return [
            "order_id" => $this->id,
            "customer_first_name" => $this->customer->first_name,
            "customer_last_name" => $this->customer->last_name,
            "customer_email" => $this->customer->email_address,
            "value" => $value
        ];
    }
}
