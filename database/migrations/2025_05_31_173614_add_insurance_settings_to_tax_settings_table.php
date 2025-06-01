<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Import DB Facade

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Không cần thay đổi cấu trúc bảng, chỉ thêm dữ liệu
        DB::table('tax_settings')->insert([
            [
                'setting_key' => 'bhxh_employee_rate',
                'setting_value' => 8.00, // 8% BHXH (người lao động đóng)
                'description' => 'Tỷ lệ đóng Bảo hiểm xã hội của người lao động (%)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'bhyc_employee_rate',
                'setting_value' => 1.50, // 1.5% BHYT (người lao động đóng)
                'description' => 'Tỷ lệ đóng Bảo hiểm y tế của người lao động (%)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'bhtn_employee_rate',
                'setting_value' => 1.00, // 1% BHTN (người lao động đóng)
                'description' => 'Tỷ lệ đóng Bảo hiểm thất nghiệp của người lao động (%)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'insurance_base_cap',
                'setting_value' => 36000000.00, // Ví dụ: 20 lần mức lương tối thiểu vùng (1.8tr * 20 = 36tr) - Cần cập nhật theo luật mới nhất
                'description' => 'Mức trần tiền lương đóng bảo hiểm xã hội, bảo hiểm y tế, bảo hiểm thất nghiệp (VNĐ/tháng). Thường là 20 lần mức lương cơ sở.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'regional_minimum_wage',
                'setting_value' => 4680000.00, // Ví dụ: Lương tối thiểu vùng I
                'description' => 'Mức lương tối thiểu vùng hiện hành (VNĐ/tháng), dùng để tính BHTN cho một số trường hợp và làm cơ sở cho insurance_base_cap.',
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
        // Xóa các cài đặt đã thêm khi rollback
        DB::table('tax_settings')->whereIn('setting_key', [
            'bhxh_employee_rate',
            'bhyc_employee_rate',
            'bhtn_employee_rate',
            'insurance_base_cap',
            'regional_minimum_wage',
        ])->delete();
    }
};