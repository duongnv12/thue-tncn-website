<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaxSetting;
use Illuminate\Support\Facades\Log; // Để ghi log lỗi

class TaxSettingController extends Controller
{
    /**
     * Hiển thị trang quản lý cài đặt thuế.
     */
    public function index()
    {
        $personalDeduction = TaxSetting::where('setting_key', 'personal_deduction_monthly')->first();
        $dependentDeduction = TaxSetting::where('setting_key', 'dependent_deduction_monthly')->first();

        // Nếu chưa có, tạo mặc định (đề phòng trường hợp migration ban đầu không chạy)
        if (!$personalDeduction) {
            $personalDeduction = TaxSetting::create([
                'setting_key' => 'personal_deduction_monthly',
                'setting_value' => 11000000,
                'description' => 'Mức giảm trừ gia cảnh cho bản thân (hàng tháng)',
                'effective_year' => 2020,
                'effective_date' => '2020-07-01',
            ]);
        }
        if (!$dependentDeduction) {
            $dependentDeduction = TaxSetting::create([
                'setting_key' => 'dependent_deduction_monthly',
                'setting_value' => 4400000,
                'description' => 'Mức giảm trừ gia cảnh cho mỗi người phụ thuộc (hàng tháng)',
                'effective_year' => 2020,
                'effective_date' => '2020-07-01',
            ]);
        }

        return view('tax_settings.index', compact('personalDeduction', 'dependentDeduction'));
    }

    /**
     * Cập nhật các cài đặt thuế.
     */
    public function update(Request $request)
    {
        $request->validate([
            'personal_deduction_monthly' => 'required|numeric|min:0',
            'dependent_deduction_monthly' => 'required|numeric|min:0',
            // Có thể thêm validation cho effective_year, effective_date nếu cho phép người dùng thay đổi
        ]);

        try {
            // Cập nhật mức giảm trừ bản thân
            TaxSetting::where('setting_key', 'personal_deduction_monthly')->update([
                'setting_value' => $request->input('personal_deduction_monthly'),
                'updated_at' => now(),
            ]);

            // Cập nhật mức giảm trừ người phụ thuộc
            TaxSetting::where('setting_key', 'dependent_deduction_monthly')->update([
                'setting_value' => $request->input('dependent_deduction_monthly'),
                'updated_at' => now(),
            ]);

            return redirect()->route('tax_settings.index')->with('success', 'Cài đặt thuế đã được cập nhật thành công!');
        } catch (\Exception $e) {
            Log::error("Error updating tax settings: " . $e->getMessage());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi cập nhật cài đặt thuế. Vui lòng thử lại.');
        }
    }
}