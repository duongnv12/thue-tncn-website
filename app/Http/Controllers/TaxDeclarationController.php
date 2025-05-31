<?php

namespace App\Http\Controllers;

use App\Models\TaxDeclaration;
use App\Services\TaxCalculatorService; // Cần thiết cho thống kê
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Barryvdh\DomPDF\Facade\Pdf; // Import the PDF Facade

class TaxDeclarationController extends Controller
{
    use AuthorizesRequests;
    protected $taxCalculatorService;

    public function __construct(TaxCalculatorService $taxCalculatorService)
    {
        $this->taxCalculatorService = $taxCalculatorService;
    }

    /**
     * Hiển thị lịch sử các khai báo thuế của người dùng.
     */
    public function index()
    {
        $declarations = Auth::user()->taxDeclarations()->orderBy('declaration_year', 'desc')->orderBy('declaration_month', 'desc')->get();
        return view('tax_declarations.index', compact('declarations'));
    }

    /**
     * Hiển thị chi tiết một khai báo thuế.
     */
    public function show(TaxDeclaration $declaration)
    {
        // Đảm bảo người dùng chỉ có thể xem khai báo của chính họ
        $this->authorize('view', $declaration); // Sử dụng Policy

        return view('tax_declarations.show', compact('declaration'));
    }

    /**
     * Xóa một khai báo thuế.
     */
    public function destroy(TaxDeclaration $declaration)
    {
        // Đảm bảo người dùng chỉ có thể xóa khai báo của chính họ
        $this->authorize('delete', $declaration); // Sử dụng Policy

        $declaration->delete();
        return redirect()->route('tax_declarations.index')->with('success', 'Khai báo thuế đã được xóa.');
    }

    /**
     * Hiển thị thống kê thuế hàng năm của người dùng.
     */
    public function statistics()
    {
        $yearlyStats = $this->taxCalculatorService->getYearlyTaxStatistics(Auth::id());
        return view('tax_calculation.statistics', compact('yearlyStats'));
    }

    /**
     * Xuất PDF cho một khai báo thuế cụ thể.
     */
    public function exportPdf(TaxDeclaration $declaration)
    {
        // Đảm bảo người dùng chỉ có thể xuất PDF khai báo của chính họ
        $this->authorize('view', $declaration); // Dùng lại policy 'view'

        $user = $declaration->user;
        // Lấy tất cả income_sources và dependents của user đó (để hiển thị chi tiết trong PDF)
        $incomeSources = $user->incomeSources()->where('frequency', 'monthly')->get(); // Lấy tất cả nguồn thu nhập hàng tháng
        $dependents = $user->dependents()->get();

        $data = [
            'declaration' => $declaration,
            'user' => $user,
            'incomeSources' => $incomeSources,
            'dependents' => $dependents,
        ];

        $pdf = Pdf::loadView('pdfs.tax_declaration_summary', $data);

        $filename = 'Khai_bao_thue_TNCN_Thang_' . $declaration->declaration_month . '_' . $declaration->declaration_year . '.pdf';

        return $pdf->download($filename); // Hoặc return $pdf->stream($filename); để hiển thị trên trình duyệt
    }
}