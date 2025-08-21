<?php

namespace src\Infrastructure\Persistence\Product;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;
    protected $table = 'products';
    public $incrementing = false;
//    protected $keyType = 'string';
    protected $fillable = [
        'id', 'name', 'price'
    ];

    protected $casts = [
        'price' => 'float'
    ];

    protected static function newFactory(){
        return ProductFactory::new();
    }
}
