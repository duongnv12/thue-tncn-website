<?php

namespace App\Http\Controllers;

use App\Models\TaxDeclaration;
use App\Services\TaxCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // Import DomPDF Facade
use Illuminate\Support\Str;

class TaxDeclarationController extends Controller
{
    protected $taxCalculatorService;

    public function __construct(TaxCalculatorService $taxCalculatorService)
    {
        $this->taxCalculatorService = $taxCalculatorService;
    }

    public function index()
    {
        $declarations = Auth::user()->taxDeclarations()->orderBy('declaration_year', 'desc')->orderBy('declaration_month', 'desc')->get();
        return view('tax_declarations.index', compact('declarations'));
    }

    public function show(TaxDeclaration $declaration)
    {
        if (Auth::id() !== $declaration->user_id) {
            abort(403, 'Bạn không có quyền xem khai báo này.');
        }

        $grossIncome = $declaration->total_income;
        $calculatedTax = $declaration->calculated_tax;
        $taxableIncome = $declaration->taxable_income;
        $mandatoryInsuranceDeductions = $declaration->mandatory_insurance_deductions;
        $personalDeductionAmount = $declaration->personal_deduction_amount;
        $dependentDeductionAmount = $declaration->dependent_deduction_amount;

        // Lấy số người phụ thuộc tại thời điểm khai báo (nếu có cột này trong TaxDeclaration)
        // Nếu không có, bạn có thể lấy từ số người phụ thuộc hiện tại của user, nhưng cần lưu ý tính chính xác.
        // Để demo, chúng ta sẽ tạm thời lấy từ số người phụ thuộc hiện tại nếu bạn chưa có cột đó.
        // Lý tưởng là cột `num_dependents` trong `tax_declarations`
        $numDependents = Auth::user()->dependents()->count(); // Tạm thời

        return view('tax_declarations.show', [
            'declaration' => $declaration,
            'grossIncome' => $grossIncome,
            'calculatedTax' => $calculatedTax,
            'taxableIncome' => $taxableIncome,
            'mandatoryInsuranceDeductions' => $mandatoryInsuranceDeductions,
            'personalDeductionAmount' => $personalDeductionAmount,
            'dependentDeductionAmount' => $dependentDeductionAmount,
            'numDependents' => $numDependents,
            'taxCalculatorService' => $this->taxCalculatorService,
        ]);
    }

    public function destroy(TaxDeclaration $declaration)
    {
        if (Auth::id() !== $declaration->user_id) {
            abort(403, 'Bạn không có quyền xóa khai báo này.');
        }

        $declaration->delete();

        return redirect()->route('tax_declarations.index')->with('status', 'Khai báo thuế đã được xóa thành công.');
    }

    public function statistics()
    {
        $declarations = Auth::user()->taxDeclarations()->get();

        $availableYears = $declarations->unique('declaration_year')
                                        ->pluck('declaration_year')
                                        ->sortDesc()
                                        ->values()
                                        ->all();

        if (empty($availableYears)) {
            $availableYears = [Carbon::now()->year];
        }

        $selectedYear = request()->get('year', $availableYears[0] ?? Carbon::now()->year);

        $annualData = $declarations->groupBy('declaration_year')->map(function ($yearDeclarations) {
            return [
                'total_income_year' => $yearDeclarations->sum('total_income'),
                'total_tax_year' => $yearDeclarations->sum('calculated_tax'),
            ];
        })->sortBy('declaration_year');

        $monthlyData = $declarations->filter(function ($declaration) use ($selectedYear) {
            return $declaration->declaration_year == $selectedYear;
        })->sortBy('declaration_month')->groupBy('declaration_month')->map(function ($monthDeclarations) {
            return [
                // Sử dụng Str::upper() ở đây nếu muốn chuẩn bị dữ liệu trước khi gửi sang view
                'month' => Str::upper(Carbon::create(null, $monthDeclarations->first()->declaration_month)->locale('vi')->monthName),
                'total_income_month' => $monthDeclarations->sum('total_income'),
                'total_tax_month' => $monthDeclarations->sum('calculated_tax'),
            ];
        });

        $fullMonthlyData = collect(range(1, 12))->map(function ($monthNumber) use ($monthlyData) {
            // Sử dụng Str::upper() ở đây cho các tháng không có dữ liệu
            $monthName = Str::upper(Carbon::create(null, $monthNumber)->locale('vi')->monthName);
            $data = $monthlyData->get($monthNumber);
            return [
                'month' => $monthName,
                'total_income_month' => $data['total_income_month'] ?? 0,
                'total_tax_month' => $data['total_tax_month'] ?? 0,
            ];
        });

        return view('tax_declarations.statistics', compact('annualData', 'fullMonthlyData', 'selectedYear', 'availableYears'));
    }


    /**
     * Xuất khai báo thuế ra file PDF.
     */
    public function exportPdf(TaxDeclaration $declaration)
    {
        if (Auth::id() !== $declaration->user_id) {
            abort(403, 'Bạn không có quyền xuất khai báo này.');
        }

        // Chuẩn bị dữ liệu giống như khi hiển thị trong show method
        $grossIncome = $declaration->total_income;
        $calculatedTax = $declaration->calculated_tax;
        $taxableIncome = $declaration->taxable_income;
        $mandatoryInsuranceDeductions = $declaration->mandatory_insurance_deductions;
        $personalDeductionAmount = $declaration->personal_deduction_amount;
        $dependentDeductionAmount = $declaration->dependent_deduction_amount;
        $numDependents = Auth::user()->dependents()->count(); // Lấy số người phụ thuộc hiện tại

        $data = [
            'declaration' => $declaration,
            'grossIncome' => $grossIncome,
            'calculatedTax' => $calculatedTax,
            'taxableIncome' => $taxableIncome,
            'mandatoryInsuranceDeductions' => $mandatoryInsuranceDeductions,
            'personalDeductionAmount' => $personalDeductionAmount,
            'dependentDeductionAmount' => $dependentDeductionAmount,
            'numDependents' => $numDependents,
            'taxCalculatorService' => $this->taxCalculatorService, // Truyền service để lấy tax brackets, etc.
            // Thêm các biến khác nếu cần cho PDF, ví dụ: tên user
            'user' => Auth::user(),
        ];

        // Tải view 'pdf.tax_declaration' với dữ liệu và tạo PDF
        // Bạn cần tạo file `resources/views/pdf/tax_declaration.blade.php`
        $pdf = Pdf::loadView('pdf.tax_declaration', $data);

        // Tùy chỉnh tên file
        $fileName = 'khai_bao_thue_tncn_' . $declaration->declaration_month . '_' . $declaration->declaration_year . '_' . Auth::user()->id . '.pdf';

        return $pdf->download($fileName);
    }
}