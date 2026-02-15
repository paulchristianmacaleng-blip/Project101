<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreOrder extends Model
{
    protected $table = 'tbl_pre_order';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'StudentID',
        'OrderID',
        'Items',
        'Status',
        'TotalPrice',
        'OrderDate',
        'expires_at',
    ];

    protected $casts = [
        'Items' => 'array', // Automatically cast JSON to array
        'OrderDate' => 'datetime',
        'expires_at' => 'datetime',
        'TotalPrice' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'StudentID', 'StudentID');
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

        // Count existing pre-orders for this student
        $count = self::where('StudentID', $studentId)->count();
        
        // Generate OrderID as "LRN-{count+1}"
        return $student->LRN . '-' . ($count + 1);
    }

    /**
     * Create a pre-order from cart items
     * Items should be array of ['name' => '', 'quantity' => '', 'price' => '']
     */
    public static function createFromCart($studentId, $cartItems, $totalPrice)
    {
        $orderId = self::generateOrderID($studentId);

        // Compile item names with quantities for the Items JSON field
        $compiledItems = [];
        foreach ($cartItems as $item) {
            $product = Product::find($item['id']);
            if ($product) {
                $compiledItems[] = [
                    'ProductID' => $product->ProductID,
                    'ProductName' => $product->ProductName,
                    'Quantity' => $item['quantity'],
                    'Price' => $product->Price,
                ];
            }
        }

        return self::create([
            'StudentID' => $studentId,
            'OrderID' => $orderId,
            'Items' => $compiledItems,
            'Status' => 'PENDING',
            'TotalPrice' => $totalPrice,
            'OrderDate' => now(),
            'expires_at' => now()->addMinutes(30), // 30 minute timer
        ]);
    }
}
