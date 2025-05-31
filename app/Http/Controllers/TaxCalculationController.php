<?php

namespace App\Http\Controllers;

use App\Services\TaxCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Import Carbon để làm việc với ngày tháng

class TaxCalculationController extends Controller
{
    protected $taxCalculatorService;

    public function __construct(TaxCalculatorService $taxCalculatorService)
    {
        $this->taxCalculatorService = $taxCalculatorService;
    }

    /**
     * Hiển thị form tính thuế và kết quả (nếu có).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $totalIncome = 0;
        $numDependents = $user->dependents()->count();
        $calculatedTax = null;
        $totalDeductions = null;
        $taxableIncome = null;

        // Lấy tháng và năm hiện tại để hiển thị mặc định
        $currentMonth = $request->input('month', Carbon::now()->month);
        $currentYear = $request->input('year', Carbon::now()->year);

        // Tổng hợp thu nhập hàng tháng từ tất cả các nguồn
        // Giả định 'amount' là thu nhập hàng tháng cho 'monthly' frequency
        // Đối với 'yearly' hoặc 'one-time', cần quy đổi
        // **Để đơn giản hóa, hiện tại chúng ta chỉ xem xét các nguồn thu nhập có tần suất là 'monthly'.**
        // **Trong tương lai, bạn có thể phát triển logic để quy đổi các tần suất khác.**
        $monthlyIncomeSources = $user->incomeSources()->where('frequency', 'monthly')->get();
        foreach ($monthlyIncomeSources as $source) {
            $totalIncome += $source->amount;
        }

        // Nếu người dùng yêu cầu tính toán (có POST request hoặc các tham số cụ thể)
        if ($request->isMethod('post') || $request->has(['calculate_month', 'calculate_year'])) {
            // Lấy tháng và năm từ request nếu người dùng chọn
            $monthToCalculate = $request->input('calculate_month', $currentMonth);
            $yearToCalculate = $request->input('calculate_year', $currentYear);

            // Tính toán thu nhập chịu thuế
            $totalDeductions = $this->taxCalculatorService->calculateTotalDeductions($numDependents);
            $taxableIncome = $this->taxCalculatorService->calculateTaxableIncome($totalIncome, $numDependents);

            // Tính toán thuế TNCN
            $calculatedTax = $this->taxCalculatorService->calculatePIT($taxableIncome);

            // Tùy chọn: Lưu khai báo ngay sau khi tính
            // $this->taxCalculatorService->saveDeclaration([
            //     'user_id' => $user->id,
            //     'total_income' => $totalIncome,
            //     'total_deductions' => $totalDeductions,
            //     'taxable_income' => $taxableIncome,
            //     'calculated_tax' => $calculatedTax,
            //     'declaration_month' => $monthToCalculate,
            //     'declaration_year' => $yearToCalculate,
            // ]);

            // return redirect()->route('tax_calculation.index')->with('success', 'Đã tính thuế thành công!');
        }

        return view('tax_calculation.index', compact(
            'totalIncome',
            'numDependents',
            'calculatedTax',
            'totalDeductions',
            'taxableIncome',
            'currentMonth',
            'currentYear'
        ));
    }

    /**
     * Xử lý yêu cầu tính toán thuế và lưu khai báo.
     */
    public function calculateAndSave(Request $request)
    {
        $user = Auth::user();

        // Lấy tháng và năm hiện tại để lưu khai báo
        $declarationMonth = Carbon::now()->month;
        $declarationYear = Carbon::now()->year;

        // Tổng hợp thu nhập hàng tháng (chỉ 'monthly' frequency)
        $totalIncome = $user->incomeSources()
                            ->where('frequency', 'monthly')
                            ->sum('amount'); // Dùng sum() trực tiếp trên Collection

        $numDependents = $user->dependents()->count();

        // Tính toán các giá trị
        $totalDeductions = $this->taxCalculatorService->calculateTotalDeductions($numDependents);
        $taxableIncome = $this->taxCalculatorService->calculateTaxableIncome($totalIncome, $numDependents);
        $calculatedTax = $this->taxCalculatorService->calculatePIT($taxableIncome);

        // Lưu khai báo thuế
        $this->taxCalculatorService->saveDeclaration([
            'user_id' => $user->id,
            'total_income' => $totalIncome,
            'total_deductions' => $totalDeductions,
            'taxable_income' => $taxableIncome,
            'calculated_tax' => $calculatedTax,
            'declaration_month' => $declarationMonth,
            'declaration_year' => $declarationYear,
        ]);

        return redirect()->route('tax_declarations.index')->with('success', 'Khai báo thuế đã được lưu thành công!');
    }

    /**
     * Hiển thị lịch sử các khai báo thuế của người dùng.
     */
    public function declarations()
    {
        $declarations = Auth::user()->taxDeclarations()->orderBy('declaration_year', 'desc')->orderBy('declaration_month', 'desc')->get();
        return view('tax_declarations.index', compact('declarations'));
    }

    /**
     * Hiển thị thống kê thuế hàng năm của người dùng.
     */
    public function statistics()
    {
        $yearlyStats = $this->taxCalculatorService->getYearlyTaxStatistics(Auth::id());
        return view('tax_calculation.statistics', compact('yearlyStats'));
    }
}