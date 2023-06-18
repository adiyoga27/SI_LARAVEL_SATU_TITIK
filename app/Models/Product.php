<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'image',
        'hpp',
        'price',
        'discount',
        'is_active',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'category_id' => 'integer',
        'name' => 'string',
        'discount' => 'integer',
        'price' => 'integer',
        'is_active' => 'bool',
        'hpp' => 'integer'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
