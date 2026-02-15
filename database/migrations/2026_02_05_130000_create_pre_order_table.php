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
        Schema::create('tbl_pre_order', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('StudentID')->nullable();
            $table->string('OrderID', 50)->unique(); // Format: "LRN-1", "LRN-2", etc.
            $table->json('Items'); // Array of compiled product items (names and quantities)
            $table->string('Status', 50)->default('PENDING'); // PENDING, CONFIRMED, CANCELLED, etc.
            $table->decimal('TotalPrice', 10, 2)->default(0);
            $table->dateTime('OrderDate')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pre_order');
    }
};
