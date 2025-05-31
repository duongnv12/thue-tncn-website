<?php

namespace App\Http\Controllers;

use App\Models\IncomeSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Để lấy thông tin người dùng hiện tại
use Illuminate\Validation\Rule; // Để sử dụng validation Rule

class IncomeSourceController extends Controller
{
    /**
     * Hiển thị danh sách các nguồn thu nhập của người dùng hiện tại.
     */
    public function index()
    {
        // Lấy tất cả nguồn thu nhập thuộc về người dùng đang đăng nhập, sắp xếp theo năm giảm dần
        $incomeSources = Auth::user()->incomeSources()->orderBy('year', 'desc')->get();

        return view('income_sources.index', compact('incomeSources'));
    }

    /**
     * Hiển thị form để tạo mới một nguồn thu nhập.
     */
    public function create()
    {
        return view('income_sources.create');
    }

    /**
     * Lưu trữ một nguồn thu nhập mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'source_name' => 'required|string|max:255',
            'type' => ['required', 'string', Rule::in(['salary', 'business', 'capital_investment', 'other'])], // Thêm các loại thu nhập nếu cần
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1), // Năm hợp lệ
            'total_taxable_income' => 'required|numeric|min:0',
            'tax_withheld' => 'nullable|numeric|min:0', // Có thể không có thuế khấu trừ
            'notes' => 'nullable|string|max:1000',
        ]);

        Auth::user()->incomeSources()->create($validated);

        return redirect()->route('income_sources.index')->with('success', 'Nguồn thu nhập đã được thêm thành công!');
    }

    /**
     * Hiển thị form để chỉnh sửa một nguồn thu nhập.
     * @param  \App\Models\IncomeSource  $incomeSource
     * @return \Illuminate\Http\Response
     */
    public function edit(IncomeSource $incomeSource)
    {
        // Đảm bảo người dùng chỉ có thể chỉnh sửa nguồn thu nhập của chính họ
        if ($incomeSource->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập nguồn thu nhập này.');
        }

        return response(view('income_sources.edit', compact('incomeSource')));
    }

    /**
     * Cập nhật một nguồn thu nhập đã tồn tại trong cơ sở dữ liệu.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\IncomeSource  $incomeSource
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IncomeSource $incomeSource)
    {
        // Đảm bảo người dùng chỉ có thể cập nhật nguồn thu nhập của chính họ
        if ($incomeSource->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền cập nhật nguồn thu nhập này.');
        }

        $validated = $request->validate([
            'source_name' => 'required|string|max:255',
            'type' => ['required', 'string', Rule::in(['salary', 'business', 'capital_investment', 'other'])],
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'total_taxable_income' => 'required|numeric|min:0',
            'tax_withheld' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $incomeSource->update($validated);

        return response(
            redirect()->route('income_sources.index')->with('success', 'Nguồn thu nhập đã được cập nhật thành công!')
        );
    }

    /**
     * Xóa một nguồn thu nhập khỏi cơ sở dữ liệu.
     * @param  \App\Models\IncomeSource  $incomeSource
     * @return \Illuminate\Http\Response
     */
    public function destroy(IncomeSource $incomeSource)
    {
        // Đảm bảo người dùng chỉ có thể xóa nguồn thu nhập của chính họ
        if ($incomeSource->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xóa nguồn thu nhập này.');
        }

        $incomeSource->delete();

        return response(
            redirect()->route('income_sources.index')->with('success', 'Nguồn thu nhập đã được xóa thành công!')
        );
    }
}