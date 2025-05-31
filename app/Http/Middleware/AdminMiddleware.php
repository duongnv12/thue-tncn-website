<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Thêm dòng này
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra xem người dùng đã đăng nhập và có phải là admin không
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        // Nếu không phải admin, chuyển hướng hoặc trả về lỗi 403
        return redirect('/dashboard')->with('error', 'Bạn không có quyền truy cập trang quản trị.');
        // Hoặc
        // abort(403, 'Unauthorized access.');
    }
}