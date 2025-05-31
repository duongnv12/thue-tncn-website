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
            $table->integer('declaration_year'); // Năm quyết toán/tính toán
            $table->decimal('total_gross_income', 15, 2); // Tổng thu nhập gộp (từ tất cả các nguồn)
            $table->decimal('total_taxable_income', 15, 2); // Tổng thu nhập chịu thuế (sau khi trừ các khoản miễn thuế)
            $table->decimal('total_deductions', 15, 2); // Tổng các khoản giảm trừ (bản thân, phụ thuộc, BHXH, từ thiện)
            $table->decimal('personal_deduction', 15, 2); // Giảm trừ bản thân
            $table->decimal('dependent_deduction', 15, 2); // Giảm trừ người phụ thuộc
            $table->decimal('insurance_deduction', 15, 2); // Giảm trừ bảo hiểm bắt buộc
            $table->decimal('charitable_deduction', 15, 2); // Giảm trừ từ thiện
            $table->decimal('tax_base_income', 15, 2); // Thu nhập tính thuế (sau khi trừ giảm trừ)
            $table->decimal('total_tax_payable', 15, 2); // Tổng số thuế phải nộp trong năm
            $table->decimal('total_tax_withheld', 15, 2); // Tổng số thuế đã khấu trừ tại nguồn
            $table->decimal('tax_difference', 15, 2); // Chênh lệch (nộp thêm/hoàn lại)
            $table->string('status')->default('draft'); // Trạng thái (draft, final, submitted)
            $table->text('details_json')->nullable(); // Lưu trữ chi tiết các nguồn thu nhập liên quan dưới dạng JSON (tùy chọn)
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