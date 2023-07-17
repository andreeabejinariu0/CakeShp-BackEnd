<?php

namespace App\Models;
use App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProducts extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $filltable = ['product_id', 'order_id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
