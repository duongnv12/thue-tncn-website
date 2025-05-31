<?php

namespace App\Http\Controllers;

use App\Models\Dependent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DependentController extends Controller
{
    /**
     * Hiển thị danh sách người phụ thuộc của người dùng hiện tại.
     */
    public function index()
    {
        $dependents = Auth::user()->dependents()->get();
        return view('dependents.index', compact('dependents'));
    }

    /**
     * Hiển thị form để tạo mới một người phụ thuộc.
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
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'citizen_id' => 'nullable|string|max:20',
            'relationship' => ['required', 'string', Rule::in(['con', 'vo', 'chong', 'cha', 'me', 'khac'])],
            'months_registered' => 'required|integer|min:1|max:12',
            'notes' => 'nullable|string|max:1000',
        ]);

        Auth::user()->dependents()->create($validated);

        return redirect()->route('dependents.index')->with('success', 'Người phụ thuộc đã được thêm thành công!');
    }

    /**
     * Hiển thị form để chỉnh sửa một người phụ thuộc.
     */
    public function edit(Dependent $dependent)
    {
        if ($dependent->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập người phụ thuộc này.');
        }
        return view('dependents.edit', compact('dependent'));
    }

    /**
     * Cập nhật một người phụ thuộc đã tồn tại trong cơ sở dữ liệu.
     */
    public function update(Request $request, Dependent $dependent)
    {
        if ($dependent->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền cập nhật người phụ thuộc này.');
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'citizen_id' => 'nullable|string|max:20',
            'relationship' => ['required', 'string', Rule::in(['con', 'vo', 'chong', 'cha', 'me', 'khac'])],
            'months_registered' => 'required|integer|min:1|max:12',
            'notes' => 'nullable|string|max:1000',
        ]);

        $dependent->update($validated);

        return redirect()->route('dependents.index')->with('success', 'Người phụ thuộc đã được cập nhật thành công!');
    }

    /**
     * Xóa một người phụ thuộc khỏi cơ sở dữ liệu.
     */
    public function destroy(Dependent $dependent)
    {
        if ($dependent->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xóa người phụ thuộc này.');
        }

        $dependent->delete();

        return redirect()->route('dependents.index')->with('success', 'Người phụ thuộc đã được xóa thành công!');
    }
}