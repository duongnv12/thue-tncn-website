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
        Schema::create('tax_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key')->unique(); // Ví dụ: 'personal_deduction', 'dependent_deduction'
            $table->decimal('setting_value', 15, 2); // Giá trị của thiết lập (tiền)
            $table->string('description')->nullable(); // Mô tả
            $table->timestamps();
        });

        // Thêm dữ liệu mặc định vào bảng tax_settings
        // Đây là ví dụ dựa trên quy định hiện hành của Việt Nam
        \Illuminate\Support\Facades\DB::table('tax_settings')->insert([
            [
                'setting_key' => 'personal_deduction',
                'setting_value' => 11000000.00, // 11 triệu VNĐ/tháng
                'description' => 'Mức giảm trừ gia cảnh cho bản thân người nộp thuế (VNĐ/tháng)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'dependent_deduction',
                'setting_value' => 4400000.00, // 4.4 triệu VNĐ/tháng
                'description' => 'Mức giảm trừ gia cảnh cho mỗi người phụ thuộc (VNĐ/tháng)',
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
        Schema::dropIfExists('tax_settings');
    }
};