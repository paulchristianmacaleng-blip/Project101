<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesData extends Model
{
    // Table name
    protected $table = 'tbl_sales_data';
    // Primary key
    protected $primaryKey = 'SalesID';
    // Timestamps
    public $timestamps = false;
    // Fillable fields
    protected $fillable = [
        'StudentID',
        'PaymentMethodID',
        'CardAmount',
        'CashGiven',
        'TotalAmount',
        'ProductID',
        'Quantity',
        'Price',
        'Subtotal',
        'DateCreated',
        'ReceiptNo',
    ];
    // If you want to use created_at, updated_at, set $timestamps = true and map them if needed
    // const CREATED_AT = 'DateCreated';

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID', 'ProductID');
    }
}
