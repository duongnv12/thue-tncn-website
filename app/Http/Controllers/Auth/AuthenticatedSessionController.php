<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User; // Thêm dòng này để sử dụng Model User

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Lấy thông tin người dùng đã đăng nhập
        $user = Auth::user();

        // Kiểm tra xem người dùng có vai trò admin không
        if ($user && $user->is_admin) {
            // Nếu là admin, chuyển hướng đến admin dashboard
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        // Nếu không phải admin, chuyển hướng đến dashboard mặc định (hoặc trang chủ)
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}