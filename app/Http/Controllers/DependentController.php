<?php

namespace App\Http\Controllers;

use App\Models\Dependent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;

class DependentController extends Controller
{
    use AuthorizesRequests;
    /**
     * Hiển thị danh sách người phụ thuộc của người dùng hiện tại.
     */
    public function index()
    {
        $dependents = Auth::user()->dependents()->get();
        return view('dependents.index', compact('dependents'));
    }

    /**
     * Hiển thị form để tạo người phụ thuộc mới.
     */
    public function create()
    {
        return view('dependents.create');
    }

    /**
     * Lưu trữ một người phụ thuộc mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before_or_equal:today',
            'relationship' => 'required|string|max:255',
            'tax_code' => 'nullable|string|max:20|unique:dependents,tax_code,NULL,id,user_id,' . Auth::id(), // Đảm bảo MST là duy nhất cho mỗi người dùng
        ]);

        Auth::user()->dependents()->create([
            'name' => $request->name,
            'date_of_birth' => $request->date_of_birth,
            'relationship' => $request->relationship,
            'tax_code' => $request->tax_code,
        ]);

        return redirect()->route('dependents.index')->with('success', 'Người phụ thuộc đã được thêm thành công!');
    }

    /**
     * Hiển thị form để chỉnh sửa một người phụ thuộc.
     */
    public function edit(Dependent $dependent)
    {
        // Đảm bảo người dùng chỉ có thể sửa người phụ thuộc của chính họ
        $this->authorize('update', $dependent); // Sử dụng Laravel Policies nếu bạn đã tạo

        return view('dependents.edit', compact('dependent'));
    }

    /**
     * Cập nhật một người phụ thuộc trong cơ sở dữ liệu.
     */
    public function update(Request $request, Dependent $dependent)
    {
        // Đảm bảo người dùng chỉ có thể cập nhật người phụ thuộc của chính họ
        $this->authorize('update', $dependent); // Sử dụng Laravel Policies nếu bạn đã tạo

        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before_or_equal:today',
            'relationship' => 'required|string|max:255',
            'tax_code' => ['nullable', 'string', 'max:20', Rule::unique('dependents')->ignore($dependent->id)], // Đảm bảo MST là duy nhất
        ]);

        $dependent->update([
            'name' => $request->name,
            'date_of_birth' => $request->date_of_birth,
            'relationship' => $request->relationship,
            'tax_code' => $request->tax_code,
        ]);

        return redirect()->route('dependents.index')->with('success', 'Người phụ thuộc đã được cập nhật thành công!');
    }

    /**
     * Xóa một người phụ thuộc khỏi cơ sở dữ liệu.
     */
    public function destroy(Dependent $dependent)
    {
        // Đảm bảo người dùng chỉ có thể xóa người phụ thuộc của chính họ
        $this->authorize('delete', $dependent); // Sử dụng Laravel Policies nếu bạn đã tạo

        $dependent->delete();

        return redirect()->route('dependents.index')->with('success', 'Người phụ thuộc đã được xóa thành công!');
    }
}