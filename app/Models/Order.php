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
        'table_id',
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

    protected static function booted()
    {
        static::created(function ($model) {
            $jml = $model->whereDate('created_at', date('Y-m-d'))->count();
            $prefix = (date('Ymd') * 100000) + $jml + 1;
            $model->order_number = 'INV-'.$prefix;
            $model->save();
        });
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }


}
