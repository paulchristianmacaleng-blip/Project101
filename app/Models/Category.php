<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'tbl_category';
    protected $primaryKey = 'CategoryID';
    public $timestamps = false;
    protected $fillable = [
        'CategoryName',
        'Description',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'CategoryID', 'CategoryID');
    }
}
