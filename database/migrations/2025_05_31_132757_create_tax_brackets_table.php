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
        Schema::create('tax_brackets', function (Blueprint $table) {
            $table->id();
            $table->integer('bracket_number')->unique(); // Bậc 1, 2, 3...
            $table->decimal('min_income', 15, 2); // Mức thu nhập tối thiểu của bậc (nếu là bậc đầu tiên thì 0)
            $table->decimal('max_income', 15, 2)->nullable(); // Mức thu nhập tối đa của bậc (null nếu là bậc cuối cùng)
            $table->decimal('tax_rate', 5, 2); // Tỷ lệ thuế (%)
            $table->timestamps();
        });

        // Thêm dữ liệu mặc định vào bảng tax_brackets
        // Đây là ví dụ biểu thuế lũy tiến hiện hành ở Việt Nam (cho thu nhập từ tiền lương, tiền công)
        \Illuminate\Support\Facades\DB::table('tax_brackets')->insert([
            [
                'bracket_number' => 1,
                'min_income' => 0.00,
                'max_income' => 5000000.00, // Đến 5 triệu
                'tax_rate' => 5.00, // 5%
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bracket_number' => 2,
                'min_income' => 5000001.00, // Trên 5 triệu đến 10 triệu
                'max_income' => 10000000.00,
                'tax_rate' => 10.00, // 10%
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bracket_number' => 3,
                'min_income' => 10000001.00, // Trên 10 triệu đến 18 triệu
                'max_income' => 18000000.00,
                'tax_rate' => 15.00, // 15%
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bracket_number' => 4,
                'min_income' => 18000001.00, // Trên 18 triệu đến 32 triệu
                'max_income' => 32000000.00,
                'tax_rate' => 20.00, // 20%
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bracket_number' => 5,
                'min_income' => 32000001.00, // Trên 32 triệu đến 52 triệu
                'max_income' => 52000000.00,
                'tax_rate' => 25.00, // 25%
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bracket_number' => 6,
                'min_income' => 52000001.00, // Trên 52 triệu đến 80 triệu
                'max_income' => 80000000.00,
                'tax_rate' => 30.00, // 30%
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bracket_number' => 7,
                'min_income' => 80000001.00, // Trên 80 triệu (bậc cuối cùng)
                'max_income' => null, // Không có mức tối đa
                'tax_rate' => 35.00, // 35%
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_brackets');
    }
};