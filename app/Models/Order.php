<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'tbl_orders';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'StudentID',
        'OrderID',
        'ProductID',
        'Quantity',
        'Price',
        'OrderDate',
    ];

    protected $casts = [
        'OrderDate' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'StudentID', 'StudentID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID', 'ProductID');
    }

    /**
     * Generate the next OrderID for a student based on their LRN
     * Format: LRN-1, LRN-2, etc.
     */
    public static function generateOrderID($studentId)
    {
        $student = Student::find($studentId);
        if (!$student) {
            throw new \Exception('Student not found');
        }

        // Count existing orders for this student
        $count = self::where('StudentID', $studentId)->count();
        
        // Generate OrderID as "LRN-{count+1}"
        return $student->LRN . '-' . ($count + 1);
    }
}
