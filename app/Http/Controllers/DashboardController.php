<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncomeSource; // Đảm bảo đã import
use App\Models\Dependent;    // Đảm bảo đã import

class DashboardController extends Controller
{
    /**
     * Hiển thị trang dashboard chính cho người dùng.
     */
    public function index()
    {
        // Lấy tổng số nguồn thu nhập của người dùng hiện tại
        $totalIncomeSources = auth()->user()->incomeSources()->count();

        // Lấy tổng số người phụ thuộc của người dùng hiện tại
        $totalDependents = auth()->user()->dependents()->count();

        // Bạn có thể thêm các thống kê khác tại đây nếu muốn,
        // ví dụ: tổng thu nhập đã khai báo, tổng thuế đã nộp

        return view('dashboard', compact('totalIncomeSources', 'totalDependents'));
    }
}