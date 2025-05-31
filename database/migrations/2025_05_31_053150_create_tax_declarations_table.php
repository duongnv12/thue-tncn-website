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
        Schema::create('tax_declarations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_income', 15, 2);
            $table->decimal('total_deductions', 15, 2);
            $table->decimal('taxable_income', 15, 2);
            $table->decimal('calculated_tax', 15, 2);
            $table->integer('declaration_month'); // Tháng khai báo (1-12)
            $table->integer('declaration_year'); // Năm khai báo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_declarations');
    }
};