<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Services\TaxCalculatorService; // Import Service Class
use Illuminate\Support\Facades\Auth;
use App\Models\TaxDeclaration;

class TaxCalculationController extends Controller
{
    protected $taxCalculatorService;


    public function __construct(TaxCalculatorService $taxCalculatorService)
    {
        $this->taxCalculatorService = $taxCalculatorService;
    }

    /**
     * Hiển thị trang để người dùng chọn năm tính thuế và các thông tin bổ sung.
     */
    public function index()
    {
        $currentYear = date('Y');
        // Lấy danh sách các năm mà người dùng có nguồn thu nhập để gợi ý
        $availableYears = Auth::user()->incomeSources()->distinct('year')->pluck('year')->sortDesc();

        return view('tax_calculation.index', compact('currentYear', 'availableYears'));
    }

    /**
     * Thực hiện tính toán thuế và hiển thị kết quả.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            // Thêm validation cho các khoản giảm trừ mới
            'insurance_deduction_amount' => 'nullable|numeric|min:0', // Tổng BH bắt buộc
            'charitable_deduction_amount' => 'nullable|numeric|min:0', // Tổng từ thiện
        ]);

        $year = $request->input('year');
        $user = Auth::user();

        // Lấy các khoản giảm trừ bổ sung từ request
        $insuranceDeductionAmount = (float) $request->input('insurance_deduction_amount', 0);
        $charitableDeductionAmount = (float) $request->input('charitable_deduction_amount', 0);

        // Truyền các khoản giảm trừ bổ sung vào service
        $calculationResult = $this->taxCalculatorService->calculateTax(
            $user,
            $year
        );

        $declaration = $this->taxCalculatorService->saveDeclaration($user, $calculationResult);

        return response()->view('tax_calculation.result', compact('calculationResult', 'declaration'));
    }

    /**
     * Hiển thị danh sách các bản khai báo thuế đã lưu.
     */
    public function declarations()
    {
        $declarations = Auth::user()->taxDeclarations()->orderBy('declaration_year', 'desc')->get();
        return view('tax_calculation.declarations', compact('declarations'));
    }

    /**
     * Hiển thị chi tiết một bản khai báo thuế cụ thể.
     */
    public function showDeclaration(TaxDeclaration $taxDeclaration)
    {
        // Đảm bảo người dùng chỉ xem được bản khai báo của chính họ
        if ($taxDeclaration->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập bản khai báo này.');
        }

        // Nếu có trường details_json, giải mã nó để hiển thị chi tiết hơn
        $details = json_decode($taxDeclaration->details_json, true);

        return view('tax_calculation.show', compact('taxDeclaration', 'details'));
    }

    /**
     * Xuất bản khai báo thuế dưới dạng PDF.
     */
    public function generatePdfDeclaration(TaxDeclaration $taxDeclaration)
    {
        // Đảm bảo người dùng chỉ xem được bản khai báo của chính họ
        if ($taxDeclaration->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập bản khai báo này.');
        }

        $details = json_decode($taxDeclaration->details_json, true);

        // Load view vào PDF
        $pdf = Pdf::loadView('tax_calculation.pdf_template', compact('taxDeclaration', 'details'));

        // Tên file PDF
        $filename = 'Quyet_toan_thue_TNCN_nam_' . $taxDeclaration->declaration_year . '_' . Auth::user()->name . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Hiển thị trang thống kê thu nhập và thuế.
     */
    public function statistics()
    {
        $user = Auth::user();
        // Lấy tất cả các khai báo thuế của người dùng, sắp xếp theo năm
        $declarations = $user->taxDeclarations()->orderBy('declaration_year', 'asc')->get();

        $chartData = [
            'labels' => [],
            'totalGrossIncome' => [],
            'totalTaxPayable' => [],
            'totalTaxWithheld' => [],
        ];

        foreach ($declarations as $declaration) {
            $chartData['labels'][] = $declaration->declaration_year;
            $chartData['totalGrossIncome'][] = $declaration->total_gross_income;
            $chartData['totalTaxPayable'][] = $declaration->total_tax_payable;
            $chartData['totalTaxWithheld'][] = $declaration->total_tax_withheld;
        }

        return view('tax_calculation.statistics', compact('chartData'));
    }
}