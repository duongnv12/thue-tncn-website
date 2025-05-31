<?php

namespace App\Http\Controllers;

use App\Models\IncomeSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class IncomeSourceController extends Controller
{
    use AuthorizesRequests;
    /**
     * Hiển thị danh sách các nguồn thu nhập của người dùng hiện tại.
     */
    public function index()
    {
        $incomeSources = Auth::user()->incomeSources()->get();
        return view('income_sources.index', compact('incomeSources'));
    }

    /**
     * Hiển thị form để tạo nguồn thu nhập mới.
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
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:monthly,yearly,one-time', // Validate frequency
            'description' => 'nullable|string|max:1000', // Validate description
        ]);

        Auth::user()->incomeSources()->create([
            'name' => $request->name,
            'amount' => $request->amount,
            'frequency' => $request->frequency,
            'description' => $request->description,
        ]);

        return redirect()->route('income_sources.index')->with('success', 'Nguồn thu nhập đã được thêm thành công!');
    }

    /**
     * Hiển thị form để chỉnh sửa một nguồn thu nhập.
     */
    public function edit(IncomeSource $incomeSource)
    {
        // Đảm bảo người dùng chỉ có thể sửa nguồn thu nhập của chính họ
        $this->authorize('update', $incomeSource); // Sử dụng Laravel Policies nếu bạn đã tạo

        return view('income_sources.edit', compact('incomeSource'));
    }

    /**
     * Cập nhật một nguồn thu nhập trong cơ sở dữ liệu.
     */
    public function update(Request $request, IncomeSource $incomeSource)
    {
        // Đảm bảo người dùng chỉ có thể cập nhật nguồn thu nhập của chính họ
        $this->authorize('update', $incomeSource); // Sử dụng Laravel Policies nếu bạn đã tạo

        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:monthly,yearly,one-time', // Validate frequency
            'description' => 'nullable|string|max:1000', // Validate description
        ]);

        $incomeSource->update([
            'name' => $request->name,
            'amount' => $request->amount,
            'frequency' => $request->frequency,
            'description' => $request->description,
        ]);

        return redirect()->route('income_sources.index')->with('success', 'Nguồn thu nhập đã được cập nhật thành công!');
    }

    /**
     * Xóa một nguồn thu nhập khỏi cơ sở dữ liệu.
     */
    public function destroy(IncomeSource $incomeSource)
    {
        // Đảm bảo người dùng chỉ có thể xóa nguồn thu nhập của chính họ
        $this->authorize('delete', $incomeSource); // Sử dụng Laravel Policies nếu bạn đã tạo

        $incomeSource->delete();

        return redirect()->route('income_sources.index')->with('success', 'Nguồn thu nhập đã được xóa thành công!');
    }
}