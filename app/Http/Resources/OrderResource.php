<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_number' => $this->order_number,
            'customer_name' => $this->customer_name,
            'customer_hp' => $this->customer_hp,
            'total_price' => (integer) $this->total_price,
            'discount' => (integer) $this->discount,
            'tax' => (integer) $this->tax,
            'total_payment' => (integer) $this->total_payment,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'payment_number' => $this->payment_number,
            'note' => $this->note,
            'uuid'=>$this->uuid,
            'cart' =>  OrderDetailResource::collection($this->details)
        ];
    }
}
