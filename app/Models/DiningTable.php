<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiningTable extends Model
{
    use HasFactory;
    protected $table = 'dining_tables';
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];
}
