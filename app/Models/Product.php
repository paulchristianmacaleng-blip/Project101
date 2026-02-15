<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'tbl_products';
    protected $primaryKey = 'ProductID';
    public $timestamps = false;
    protected $fillable = [
        'ProductName',
        'CategoryID',
        'Price',
        'ImagePath',
        'StockStatus',
        'TotalPieces',
        'MinimumStockLevel',
        'StocksPerUnit',
        // Add other fields if needed
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'CategoryID', 'CategoryID');
    }
}

