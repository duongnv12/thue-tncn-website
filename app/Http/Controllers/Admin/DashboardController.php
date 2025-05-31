<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash; // Để hash mật khẩu khi đặt lại
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Hiển thị trang Admin Dashboard.
     */
    public function index()
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('is_admin', true)->count();
        $recentUsers = User::orderBy('created_at', 'asc')->take(5)->get(); // Lấy 5 người dùng đầu tiên (tăng dần)

        // Thống kê số lượng người dùng đăng ký theo tháng trong năm hiện tại
        $userStats = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Đảm bảo đủ 12 tháng
        $userStatsFull = [];
        for ($i = 1; $i <= 12; $i++) {
            $userStatsFull[] = $userStats[$i] ?? 0;
        }

        return view('admin.dashboard', compact('totalUsers', 'totalAdmins', 'recentUsers', 'userStatsFull'));
    }

    /**
     * Hiển thị danh sách tất cả người dùng.
     */
    public function users()
    {
        $users = User::orderBy('created_at', 'asc')->paginate(10); // Phân trang 10 người dùng mỗi trang
        return view('admin.users.index', compact('users'));
    }

    /**
     * Hiển thị form chỉnh sửa thông tin người dùng.
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin người dùng.
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'is_admin' => 'boolean', // Đảm bảo là boolean
            'password' => 'nullable|string|min:8|confirmed', // Cho phép đặt lại mật khẩu
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_admin = $request->has('is_admin'); // Nếu checkbox được check thì là true

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Thông tin người dùng đã được cập nhật.');
    }

    /**
     * Xóa người dùng.
     */
    public function deleteUser(User $user)
    {
        // Admin không thể tự xóa tài khoản của mình
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không thể tự xóa tài khoản Admin của mình.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được xóa thành công.');
    }
}