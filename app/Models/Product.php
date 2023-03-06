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
        'price',
        'discount',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
