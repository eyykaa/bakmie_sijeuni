<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->string('order_code')->unique(); // SJN-000001
      $table->unsignedInteger('table_no');    // 1..10
      $table->integer('subtotal')->default(0);
      $table->integer('tax')->default(0);
      $table->integer('total')->default(0);
      $table->enum('payment_method', ['transfer', 'cash']);
      $table->enum('status', ['pending', 'waiting_cashier', 'paid'])->default('pending');
      $table->timestamps();
    });
  }

  public function down(): void {
    Schema::dropIfExists('orders');
  }
};