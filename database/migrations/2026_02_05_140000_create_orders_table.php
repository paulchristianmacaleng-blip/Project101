<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('StudentID')->nullable();
            $table->string('OrderID', 50); // Format: "LRN-1", "LRN-2", etc. (NOT unique - multiple items per order)
            $table->integer('ProductID')->nullable();
            $table->integer('Quantity')->default(1);
            $table->decimal('Price', 10, 2)->nullable();
            $table->dateTime('OrderDate')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_orders');
    }
};
