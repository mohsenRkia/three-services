<?php

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'id', 'name', 'price'
    ];

    protected $casts = [
        'price' => 'float'
    ];
}
