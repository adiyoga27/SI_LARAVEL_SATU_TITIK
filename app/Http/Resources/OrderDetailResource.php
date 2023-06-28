<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (integer) $this->id,
            'product_id' => (integer) $this->product_id,
            'name' => $this->product->name,
            'status' => $this->status,
            'note' => $this->note,
            'price' =>(double)  $this->price,
            'quantity' => (integer)$this->quantity,
            'discount' => (double) $this->discount,
            'total_price' => (double) $this->total_price
        ];
    }
}
