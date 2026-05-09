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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('name');
            $table->string('type'); // cash, deposito, emas, vallas, saham, lainnya
            $table->date('purchase_date')->nullable();
            $table->decimal('buy_price', 15, 2);
            $table->decimal('quantity', 15, 4);
            $table->string('ticker')->nullable();
            $table->text('note')->nullable();
            $table->decimal('current_price', 15, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
