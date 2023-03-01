<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'dining_table_id',
        'order_number',
        'customer_name',
        'customer_hp',
        'total_price',
        'discount',
        'tax',
        'total_payment',
        'status',
        'payment_method',
        'payment_number',
        'note',
        'uuid'
    ];

    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }


}
