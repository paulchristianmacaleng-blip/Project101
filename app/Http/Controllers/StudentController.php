<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\SalesData;
use App\Models\PreOrder;
use App\Models\Order;

class StudentController extends Controller
{
    public function home() {
    $student = null;
    $dailySpending = 0;
    $recentOrders = [];

    if (session()->has('student_id')) {
        $student = \App\Models\Student::find(session('student_id'));
        $dailySpending = $this->DailySpending($student->StudentID);

        // Get all orders for this student, grouped by ReceiptNo
        $orders = SalesData::with('product')
            ->where('StudentID', $student->StudentID)
            ->orderByDesc('DateCreated')
            ->get()
            ->groupBy('ReceiptNo');

        $recentOrders = collect($orders)
            ->sortByDesc(function($group) {
                return $group->first()->DateCreated;
            })
            ->take(5)
            ->map(function($group) {
                return [
                    'items' => $group->map(function($item) {
                        return [
                            'ProductID' => $item->ProductID,
                            'ProductName' => $item->product ? $item->product->ProductName : 'Unknown',
                            'Quantity' => $item->Quantity,
                            'Price' => $item->Price,
                            'SubTotal' => $item->Quantity * $item->Price, // calculate subtotal per item
                        ];
                    })->toArray(),
                    'total' => $group->sum(function($item) {
                        return $item->Quantity * $item->Price; // calculate total per receipt
                    }),
                    'date' => $group->first()->DateCreated,
                    'receipt' => $group->first()->ReceiptNo,
                ];
            })
            ->values()
            ->toArray();
    }

    return view('home', compact('student', 'dailySpending', 'recentOrders'));
}

    public function shop() {
        $student = null;
        if (session()->has('student_id')) {
            $student = \App\Models\Student::find(session('student_id'));
        }
        
        // Get all categories
        $categories = \App\Models\Category::all();
        
        // Get selected category from query parameter
        $selectedCategory = request('category');
        
        // Fetch products with optional category filter
        $query = \App\Models\Product::query();
        if ($selectedCategory && $selectedCategory != 'all') {
            $query->where('CategoryID', $selectedCategory);
        }
        $products = $query->get();
        
        return view('shop', compact('student', 'products', 'categories', 'selectedCategory'));
    }
    public function credit() {
        $student = null;
        if (session()->has('student_id')) {
            $student = \App\Models\Student::find(session('student_id'));
        }
        return view('credit', compact('student'));
    }
    
    public function DailySpending($studentId)
    {
        return SalesData::where('StudentID', $studentId)
            ->whereDate('DateCreated', now()->toDateString())
            ->sum('SubTotal');
    }
    public function transaction() {
        $student = null;
        $recentOrder = null;
        $allPurchases = [];
        if (session()->has('student_id')) {
            $student = \App\Models\Student::find(session('student_id'));
            // Get the most recent order (grouped by ReceiptNo)
            $orderGroup = \App\Models\SalesData::with('product')
                ->where('StudentID', $student->StudentID)
                ->orderByDesc('DateCreated')
                ->get()
                ->groupBy('ReceiptNo')
                ->sortByDesc(function($group) {
                    return $group->first()->DateCreated;
                })
                ->first();
            if ($orderGroup) {
                $recentOrder = collect($orderGroup)->map(function($item) {
                    return [
                        'ProductName' => $item->product ? $item->product->ProductName : 'Unknown',
                        'Quantity' => $item->Quantity,
                        'Price' => $item->Price,
                    ];
                })->toArray();
            }

            // Handle search, sort, and filter for transaction history
            $query = \App\Models\SalesData::with('product')
                ->where('StudentID', $student->StudentID);

            // Search
            $search = request('search');
            if ($search) {
                $query->whereHas('product', function($q) use ($search) {
                    $q->where('ProductName', 'like', "%$search%");
                });
            }


            // Filter (daily, monthly, yearly, none)
            $filter = request('filter');
            if ($filter === 'daily') {
                $query->whereDate('DateCreated', now()->toDateString());
            } elseif ($filter === 'monthly') {
                $query->whereMonth('DateCreated', now()->month)
                      ->whereYear('DateCreated', now()->year);
            } elseif ($filter === 'yearly') {
                $query->whereYear('DateCreated', now()->year);
            } // 'none' or empty: no filter

            // Sort (date/price, none)
            $sort = request('sort');
            if ($sort === 'date_asc') {
                $query->orderBy('DateCreated', 'asc');
            } elseif ($sort === 'date_desc') {
                $query->orderBy('DateCreated', 'desc');
            } elseif ($sort === 'price_asc') {
                $query->orderBy('Price', 'asc');
            } elseif ($sort === 'price_desc') {
                $query->orderBy('Price', 'desc');
            } // 'none' or empty: no sort, default to newest-to-oldest
            else {
                $query->orderBy('DateCreated', 'desc');
            }

            $allPurchases = $query->get()->map(function($item) {
                return [
                    'Date' => $item->DateCreated,
                    'ReceiptNo' => $item->ReceiptNo,
                    'ProductName' => $item->product ? $item->product->ProductName : 'Unknown',
                    'Quantity' => $item->Quantity,
                    'Price' => $item->Price,
                ];
            })->toArray();
        }
        return view('transaction', compact('student', 'recentOrder', 'allPurchases'));
    }
    public function setting() {
        $student = null;
        if (session()->has('student_id')) {
            $student = \App\Models\Student::find(session('student_id'));
        }
        return view('setting', compact('student'));
    }

     public function update(Request $request)
    {
        if (!session()->has('student_id')) {
            return redirect()->back()->with('error', 'Not authenticated.');
        }
        $student = Student::find(session('student_id'));
        if (!$student) {
            return redirect()->back()->with('error', 'Student not found.');
        }
        $validated = $request->validate([
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'MiddleInitial' => 'nullable|alpha|size:1',
            'gmail' => 'nullable|email|max:255',
            'Password' => 'nullable|string|min:6|confirmed',
        ]);

        $student->FirstName = $validated['FirstName'];
        $student->LastName = $validated['LastName'];
        $student->MiddleInitial = $validated['MiddleInitial'] ?? '';
        $student->gmail = $validated['gmail'] ?? '';
        if (!empty($validated['Password'])) {
            $student->PasswordHash = Hash::make($validated['Password']);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $avatar = $request->file('avatar');
            $rfid = $student->RFIDTag;
            $ext = $avatar->getClientOriginalExtension();
            $filename = $rfid . '.' . $ext;
            $avatarDir = public_path('temporary_student_images');
            // Delete old image if it exists and is not the default
            if (!empty($student->ImagePath)) {
                $oldPath = $avatarDir . DIRECTORY_SEPARATOR . $student->ImagePath;
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $avatar->move($avatarDir, $filename);
            $student->ImagePath = $filename;
        }

        $student->save();
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
    /**
     * Handle student login by LRN or Username.
     */
    public function studentLogin(Request $request)
    {
        $logcredential = $request->input('logcredential');
        $password = $request->input('password');
        $student = Student::student_credentials($logcredential);

        if ($student) {
            // Check if the hash is bcrypt (starts with $2y$ or $2a$)
            $isBcrypt = preg_match('/^\$2[ayb]\$/', $student->PasswordHash);
            if (($isBcrypt && Hash::check($password, $student->PasswordHash)) ||
                (!$isBcrypt && $student->PasswordHash === $password)) {
                // Login successful (bcrypt or plain text)
                session(['student_id' => $student->StudentID]);
                return redirect('/student/home');
            }
        }
        // Login failed, redirect back with error
        return redirect()->back()->with('error', 'Invalid credentials.');
    }

    /**
     * Toggle the IsBlocked status for a student (AJAX).
     */
    public function toggleLock(Request $request)
    {
        $studentId = $request->input('student_id');
        $student = Student::find($studentId);
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found.'], 404);
        }

        // Toggle the IsBlocked value
        $student->IsBlocked = $student->IsBlocked ? 0 : 1;
        $student->save();

        return response()->json([
            'success' => true,
            'isBlocked' => $student->IsBlocked,
            'message' => $student->IsBlocked ? 'Card locked.' : 'Card unlocked.'
        ]);
    }
    /**
     * Log out the student and end the session.
     */
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/');
    }

    /**
     * Handle checkout of cart items
     */
    public function checkout(Request $request)
    {
        // Check if student is authenticated
        if (!session()->has('student_id')) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to proceed with checkout.'
            ], 401);
        }

        $studentId = session('student_id');
        $student = Student::find($studentId);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found.'
            ], 404);
        }

        // Validate input
        $cartItems = $request->input('items');
        if (!is_array($cartItems) || empty($cartItems)) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty.'
            ], 400);
        }

        // Calculate total and validate stock
        $totalAmount = 0;
        $products = [];
        
        foreach ($cartItems as $item) {
            $product = \App\Models\Product::find($item['id']);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => "Product ID {$item['id']} not found."
                ], 404);
            }

            // Check stock
            // Check stock (restriction: only TotalPieces)
            $availableStock = $product->TotalPieces;
            if ($availableStock < $item['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => "Not enough stock for {$product->ProductName}. Available: {$availableStock}, Requested: {$item['quantity']}"
                ], 400);
            }

            $itemTotal = $product->Price * $item['quantity'];
            $totalAmount += $itemTotal;
            
            $products[] = [
                'product' => $product,
                'quantity' => $item['quantity'],
                'price' => $product->Price,
                'subtotal' => $itemTotal
            ];
        }

        // Check if student has sufficient balance
        if ($student->Balance < $totalAmount) {
            return response()->json([
                'success' => false,
                'message' => "Insufficient balance. You need ₱" . number_format($totalAmount, 2) . " but only have ₱" . number_format($student->Balance, 2)
            ], 400);
        }

        try {
            // Begin transaction
            DB::beginTransaction();

            // Create PreOrder with status PENDING (default)
            $preOrder = PreOrder::createFromCart($studentId, $cartItems, $totalAmount);

            // Create individual Order records for each product
            foreach ($products as $item) {
                Order::create([
                    'StudentID' => $studentId,
                    'OrderID' => $preOrder->OrderID,
                    'ProductID' => $item['product']->ProductID,
                    'Quantity' => $item['quantity'],
                    'Price' => $item['price'],
                    'OrderDate' => now(),
                ]);
            }

            // NOTE: Stock and Balance are NOT deducted yet since order status is PENDING
            // They will be deducted only when order status changes to COMPLETED

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Order created successfully! Order ID: {$preOrder->OrderID}",
                'order_id' => $preOrder->OrderID,
                'status' => $preOrder->Status,
                'total_amount' => $totalAmount
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during checkout: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getBalance() {
        if (!session()->has('student_id')) {
            return response()->json(['balance' => 0], 401);
        }

        $student = Student::find(session('student_id'));
        
        if (!$student) {
            return response()->json(['balance' => 0], 404);
        }

        return response()->json([
            'balance' => floatval($student->Balance) ?? 0
        ]);
    }

    public function pending()
    {
        $student = null;
        $pendingOrders = [];

        if (session()->has('student_id')) {
            $student = Student::find(session('student_id'));
            
            if ($student) {
                // Get only active pending orders for this student (exclude CANCELLED/COMPLETED)
                $pendingOrders = PreOrder::where('StudentID', $student->StudentID)
                    ->where('Status', 'PENDING')
                    ->orderByDesc('OrderDate')
                    ->get();

                // Also get cancelled orders to show in a separate list
                $cancelledOrders = PreOrder::where('StudentID', $student->StudentID)
                    ->where('Status', 'CANCELLED')
                    ->orderByDesc('OrderDate')
                    ->get();
            }
        }

        // Ensure existing records have an expires_at for display and timer logic
        foreach ($pendingOrders as $order) {
            if (is_null($order->expires_at)) {
                try {
                    if ($order->OrderDate) {
                        $order->expires_at = \Carbon\Carbon::parse($order->OrderDate)->addMinutes(30);
                    } else {
                        $order->expires_at = now()->addMinutes(30);
                    }
                    // Persist to DB so future requests have the value
                    $order->save();
                } catch (\Exception $e) {
                    // If saving fails, just continue and keep the calculated value in-memory
                    // so the view can still show a timer
                    if ($order->OrderDate) {
                        $order->expires_at = \Carbon\Carbon::parse($order->OrderDate)->addMinutes(30);
                    } else {
                        $order->expires_at = now()->addMinutes(30);
                    }
                }
            }
        }

        return view('pending', compact('student', 'pendingOrders', 'cancelledOrders'));
    }

    public function cancelOrder(Request $request)
    {
        if (!session()->has('student_id')) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $preOrderId = $request->input('pre_order_id');
        $preOrder = PreOrder::find($preOrderId);

        if (!$preOrder) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        if ($preOrder->StudentID != session('student_id')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            // Mark as cancelled and set expires_at to now so timers stop and won't restart on refresh
            $preOrder->Status = 'CANCELLED';
            $preOrder->expires_at = now();
            $preOrder->save();
            return response()->json(['success' => true, 'message' => 'Order cancelled successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error cancelling order'], 500);
        }
    }

}
