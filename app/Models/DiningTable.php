<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiningTable extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'dining_tables';
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];
}
