<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('net_worths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->decimal('savings', 15, 2)->default(0);        // Tabungan (nw-sav)
            $table->decimal('emergency_fund', 15, 2)->default(0); // Dana Darurat (nw-em)
            $table->decimal('investments', 15, 2)->default(0);    // Investasi (nw-inv)
            $table->decimal('other_assets', 15, 2)->default(0);   // Aset Lain (nw-oth)
            $table->decimal('debt', 15, 2)->default(0);           // Total Utang (nw-dbt)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('net_worths');
    }
};
