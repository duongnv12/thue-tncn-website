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
        Schema::create('income_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết với bảng users
            $table->string('source_name'); // Tên nguồn thu nhập (ví dụ: Công ty A, Công ty B)
            $table->string('type')->default('salary'); // Loại thu nhập (salary, business, capital_investment, etc.)
            $table->integer('year'); // Năm thu nhập
            $table->decimal('total_taxable_income', 15, 2); // Tổng thu nhập chịu thuế từ nguồn này trong năm
            $table->decimal('tax_withheld', 15, 2)->default(0); // Số thuế đã khấu trừ tại nguồn
            $table->text('notes')->nullable(); // Ghi chú thêm
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_sources');
    }
};