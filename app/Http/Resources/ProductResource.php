<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'category_id' => (integer) $this->category_id,
            'category_name' => $this->category->name,
            'name' => $this->name,
            'description' => $this->description,
            'image' => url('storage') . "/" . $this->image,
            'price' => (double) $this->price,
            'discount' => (double)  $this->discount,
        ];
    }
}
