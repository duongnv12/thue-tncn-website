<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxSetting;
use App\Models\TaxBracket;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaxSettingsController extends Controller
{
    /**
     * Hiển thị trang quản lý cài đặt thuế và các bậc thuế.
     */
    public function index()
    {
        // Lấy tất cả cài đặt và key theo setting_key để dễ dàng truy cập trong view
        $taxSettings = TaxSetting::all()->keyBy('setting_key');
        $taxBrackets = TaxBracket::orderBy('bracket_number')->get();

        return view('admin.tax_settings.index', compact('taxSettings', 'taxBrackets'));
    }

    /**
     * Cập nhật các cài đặt thuế chung (giảm trừ gia cảnh và bảo hiểm).
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'personal_deduction' => 'required|numeric|min:0',
            'dependent_deduction' => 'required|numeric|min:0',
            'bhxh_employee_rate' => 'required|numeric|min:0|max:100', // Tỷ lệ %
            'bhyc_employee_rate' => 'required|numeric|min:0|max:100', // Tỷ lệ %
            'bhtn_employee_rate' => 'required|numeric|min:0|max:100', // Tỷ lệ %
            'insurance_base_cap' => 'required|numeric|min:0', // Mức VNĐ
            'regional_minimum_wage' => 'required|numeric|min:0', // Mức VNĐ
        ]);

        // Cập nhật hoặc tạo mới các cài đặt
        TaxSetting::updateOrCreate(
            ['setting_key' => 'personal_deduction'],
            ['setting_value' => $request->personal_deduction]
        );
        TaxSetting::updateOrCreate(
            ['setting_key' => 'dependent_deduction'],
            ['setting_value' => $request->dependent_deduction]
        );
        TaxSetting::updateOrCreate(
            ['setting_key' => 'bhxh_employee_rate'],
            ['setting_value' => $request->bhxh_employee_rate]
        );
        TaxSetting::updateOrCreate(
            ['setting_key' => 'bhyc_employee_rate'],
            ['setting_value' => $request->bhyc_employee_rate]
        );
        TaxSetting::updateOrCreate(
            ['setting_key' => 'bhtn_employee_rate'],
            ['setting_value' => $request->bhtn_employee_rate]
        );
        TaxSetting::updateOrCreate(
            ['setting_key' => 'insurance_base_cap'],
            ['setting_value' => $request->insurance_base_cap]
        );
        TaxSetting::updateOrCreate(
            ['setting_key' => 'regional_minimum_wage'],
            ['setting_value' => $request->regional_minimum_wage]
        );


        return redirect()->route('admin.tax_settings.index')->with('success', 'Cài đặt thuế và bảo hiểm đã được cập nhật.');
    }

    /**
     * Hiển thị form tạo bậc thuế mới.
     */
    public function createBracket()
    {
        // Lấy bậc thuế cuối cùng để gợi ý bracket_number tiếp theo
        $lastBracket = TaxBracket::orderByDesc('bracket_number')->first();
        $nextBracketNumber = $lastBracket ? $lastBracket->bracket_number + 1 : 1;

        return view('admin.tax_settings.create_bracket', compact('nextBracketNumber'));
    }

    /**
     * Lưu bậc thuế mới vào CSDL.
     */
    public function storeBracket(Request $request)
    {
        $request->validate([
            'bracket_number' => 'required|integer|min:1|unique:tax_brackets,bracket_number',
            'min_income' => 'required|numeric|min:0',
            'max_income' => 'nullable|numeric|gt:min_income', // max_income phải lớn hơn min_income nếu có
            'tax_rate' => 'required|numeric|min:0|max:100',
        ]);

        TaxBracket::create($request->all());

        return redirect()->route('admin.tax_settings.index')->with('success', 'Bậc thuế mới đã được thêm thành công.');
    }

    /**
     * Hiển thị form sửa đổi bậc thuế.
     */
    public function editBracket(TaxBracket $bracket)
    {
        return view('admin.tax_settings.edit_bracket', compact('bracket'));
    }

    /**
     * Cập nhật thông tin bậc thuế.
     */
    public function updateBracket(Request $request, TaxBracket $bracket)
    {
        $request->validate([
            'bracket_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('tax_brackets')->ignore($bracket->id), // bỏ qua chính nó khi kiểm tra unique
            ],
            'min_income' => 'required|numeric|min:0',
            'max_income' => 'nullable|numeric|gt:min_income',
            'tax_rate' => 'required|numeric|min:0|max:100',
        ]);

        $bracket->update($request->all());

        return redirect()->route('admin.tax_settings.index')->with('success', 'Bậc thuế đã được cập nhật thành công.');
    }

    /**
     * Xóa một bậc thuế.
     */
    public function destroyBracket(TaxBracket $bracket)
    {
        $bracket->delete();
        return redirect()->route('admin.tax_settings.index')->with('success', 'Bậc thuế đã được xóa.');
    }
}