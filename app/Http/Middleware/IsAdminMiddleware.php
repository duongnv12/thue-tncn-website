<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        // Và liệu người dùng đó có phải là admin không
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        // Nếu không phải admin, chuyển hướng về dashboard hoặc trang chủ
        // Hoặc trả về lỗi 403 Forbidden
        return redirect('/dashboard')->with('error', 'Bạn không có quyền truy cập trang này.');
        // Hoặc
        // abort(403, 'Unauthorized action.');
    }
}