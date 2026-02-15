<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PaymentController;    
Route::get('/', function () {
    return view('welcome');
});





Route::middleware(['student'])->group(function () {
    Route::get('/student/home', [StudentController::class, 'home'])->name('student.home');
    Route::get('/student/shop', [StudentController::class, 'shop'])->name('student.shop');
    Route::get('/student/transaction', [StudentController::class, 'transaction'])->name('student.transaction');
    Route::get('/student/setting', [StudentController::class, 'setting'])->name('student.setting');
    Route::post('/student/setting/update', [StudentController::class, 'update'])->name('student.update');
    Route::get('/student/credit', [StudentController::class, 'credit'])->name('student.credit');
    Route::get('/student/pending', [StudentController::class, 'pending'])->name('student.pending');
    Route::post('/student/cancel-order', [StudentController::class, 'cancelOrder'])->name('student.cancel-order');
    Route::get('/student/balance', [StudentController::class, 'getBalance'])->name('student.balance');
    Route::post('/student/checkout', [StudentController::class, 'checkout'])->name('student.checkout');
    Route::post('/payment/credit', [PaymentController::class, 'creditAdd'])->name('payment.credit');
    Route::post('/payment/gcash', [PaymentController::class, 'gcashPay'])->name('payment.gcash');
});

Route::post('/webhook/paymongo', [PaymentController::class, 'handlePaymongoWebhook']);

// Student login route
Route::post('/student/login', [StudentController::class, 'studentLogin'])->name('student.login');


// AJAX route to toggle lock status
Route::post('/student/toggle-lock', [StudentController::class, 'toggleLock'])->name('student.toggleLock');

// Student logout route
Route::get('/student/logout', [StudentController::class, 'logout'])->name('student.logout');
                    
