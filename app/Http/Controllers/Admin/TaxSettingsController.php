<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxSetting;
use App\Models\TaxBracket;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Để sử dụng Rule::unique

class TaxSettingsController extends Controller
{
    /**
     * Hiển thị trang quản lý cài đặt thuế và các bậc thuế.
     */
    public function index()
    {
        $taxSettings = TaxSetting::all();
        $taxBrackets = TaxBracket::orderBy('bracket_number')->get();

        return view('admin.tax_settings.index', compact('taxSettings', 'taxBrackets'));
    }

    /**
     * Cập nhật các cài đặt thuế chung (giảm trừ gia cảnh).
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'personal_deduction' => 'required|numeric|min:0',
            'dependent_deduction' => 'required|numeric|min:0',
        ]);

        TaxSetting::updateOrCreate(
            ['setting_key' => 'personal_deduction'],
            ['setting_value' => $request->personal_deduction]
        );

        TaxSetting::updateOrCreate(
            ['setting_key' => 'dependent_deduction'],
            ['setting_value' => $request->dependent_deduction]
        );

        return redirect()->route('admin.tax_settings.index')->with('success', 'Cài đặt giảm trừ gia cảnh đã được cập nhật.');
    }

    /**
     * Hiển thị form chỉnh sửa một bậc thuế.
     */
    public function editBracket(TaxBracket $taxBracket)
    {
        return view('admin.tax_settings.edit_bracket', compact('taxBracket'));
    }

    /**
     * Cập nhật một bậc thuế.
     */
    public function updateBracket(Request $request, TaxBracket $taxBracket)
    {
        $request->validate([
            'bracket_number' => ['required', 'integer', 'min:1', Rule::unique('tax_brackets')->ignore($taxBracket->id)],
            'min_income' => 'required|numeric|min:0',
            'max_income' => 'nullable|numeric|gt:min_income', // max_income phải lớn hơn min_income nếu có
            'tax_rate' => 'required|numeric|min:0|max:100',
        ]);

        // Đảm bảo max_income là null nếu không được cung cấp (cho bậc cuối cùng)
        $max_income = $request->filled('max_income') ? $request->max_income : null;

        $taxBracket->update([
            'bracket_number' => $request->bracket_number,
            'min_income' => $request->min_income,
            'max_income' => $max_income,
            'tax_rate' => $request->tax_rate,
        ]);

        return redirect()->route('admin.tax_settings.index')->with('success', 'Bậc thuế đã được cập nhật.');
    }

    /**
     * Thêm một bậc thuế mới.
     */
    public function createBracket()
    {
        return view('admin.tax_settings.create_bracket');
    }

    public function storeBracket(Request $request)
    {
        $request->validate([
            'bracket_number' => ['required', 'integer', 'min:1', 'unique:tax_brackets,bracket_number'],
            'min_income' => 'required|numeric|min:0',
            'max_income' => 'nullable|numeric|gt:min_income',
            'tax_rate' => 'required|numeric|min:0|max:100',
        ]);

        $max_income = $request->filled('max_income') ? $request->max_income : null;

        TaxBracket::create([
            'bracket_number' => $request->bracket_number,
            'min_income' => $request->min_income,
            'max_income' => $max_income,
            'tax_rate' => $request->tax_rate,
        ]);

        return redirect()->route('admin.tax_settings.index')->with('success', 'Bậc thuế mới đã được thêm.');
    }

    /**
     * Xóa một bậc thuế.
     */
    public function deleteBracket(TaxBracket $taxBracket)
    {
        $taxBracket->delete();
        return redirect()->route('admin.tax_settings.index')->with('success', 'Bậc thuế đã được xóa.');
    }
}