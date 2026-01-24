<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->string('order_code')->unique();      // contoh: SJ-12345
      $table->unsignedInteger('table_no');         // meja

      $table->string('status')->default('baru');   // baru|diproses|selesai
      $table->string('payment_status')->default('menunggu_verifikasi'); // menunggu_verifikasi|dibayar
      $table->string('payment_method')->nullable(); // transfer|cash
      $table->unsignedBigInteger('subtotal')->default(0);
      $table->unsignedBigInteger('tax')->default(0);
      $table->unsignedBigInteger('total')->default(0);

      $table->timestamps();
    });
  }

  public function down(): void {
    Schema::dropIfExists('orders');
  }
};