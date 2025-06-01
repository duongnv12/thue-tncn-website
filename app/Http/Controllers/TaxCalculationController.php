<?php

namespace App\Http\Controllers;

use App\Models\TaxDeclaration;
use App\Models\IncomeSource;
use App\Services\TaxCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaxCalculationController extends Controller
{
    protected $taxCalculatorService;

    public function __construct(TaxCalculatorService $taxCalculatorService)
    {
        $this->taxCalculatorService = $taxCalculatorService;
    }

    public function index(Request $request)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Lấy tổng thu nhập (Gross) từ các nguồn thu nhập của người dùng hiện tại
        // Đảm bảo logic này khớp với cách bạn muốn tổng hợp thu nhập hàng tháng/tổng
        $grossIncome = Auth::user()->incomeSources()->sum('amount'); // Tổng tất cả các nguồn
        // Nếu muốn theo tháng:
        // $grossIncome = Auth::user()->incomeSources()
        //     ->whereMonth('income_date', $currentMonth)
        //     ->whereYear('income_date', $currentYear)
        //     ->sum('amount');


        $calculatedTax = 0;
        $taxableIncome = 0;
        $mandatoryInsuranceDeductions = 0;
        $numDependents = Auth::user()->dependents()->count();

        $personalDeductionAmount = $this->taxCalculatorService->getPersonalDeduction(); // Lấy giảm trừ bản thân
        $dependentDeductionAmount = $this->taxCalculatorService->getDependentDeduction() * $numDependents; // Lấy giảm trừ người phụ thuộc

        // Luôn thực hiện tính toán ngay khi truy cập trang
        if ($grossIncome > 0) {
            $mandatoryInsuranceDeductions = $this->taxCalculatorService->calculateMandatoryInsuranceDeductions($grossIncome);
            $taxableIncome = $this->taxCalculatorService->calculateTaxableIncome($grossIncome, $numDependents);
            $calculatedTax = $this->taxCalculatorService->calculatePIT($taxableIncome);
        }

        // Xử lý logic "Lưu Khai Báo Thuế tháng này"
        if ($request->isMethod('post') && $request->has('calculate_and_save') && $request->input('calculate_and_save') === 'true') {
            // Kiểm tra xem đã tồn tại khai báo cho tháng này chưa
            $existingDeclaration = TaxDeclaration::where('user_id', Auth::id())
                                                ->where('declaration_year', $currentYear)
                                                ->where('declaration_month', $currentMonth)
                                                ->first();

            if ($existingDeclaration) {
                // Cập nhật khai báo cũ
                $declaration = $existingDeclaration;
                $message = 'Khai báo thuế tháng ' . $currentMonth . '/' . $currentYear . ' đã được cập nhật thành công!';
            } else {
                // Tạo khai báo mới
                $declaration = new TaxDeclaration();
                $declaration->user_id = Auth::id();
                $declaration->declaration_year = $currentYear;
                $declaration->declaration_month = $currentMonth;
                $message = 'Khai báo thuế tháng ' . $currentMonth . '/' . $currentYear . ' đã được lưu thành công!';
            }

            $declaration->total_income = $grossIncome;
            $declaration->total_deductions = $this->taxCalculatorService->calculateTotalDeductions($grossIncome, $numDependents);
            $declaration->mandatory_insurance_deductions = $mandatoryInsuranceDeductions; // Lưu chi tiết
            $declaration->personal_deduction_amount = $personalDeductionAmount;         // Lưu chi tiết
            $declaration->dependent_deduction_amount = $dependentDeductionAmount;       // Lưu chi tiết
            $declaration->taxable_income = $taxableIncome;                              // Lưu chi tiết
            $declaration->calculated_tax = $calculatedTax;
            $declaration->save();

            return redirect()->route('tax_calculation.index')->with('status', $message);
        }

        return view('tax_calculation.index', [
            'grossIncome' => $grossIncome,
            'calculatedTax' => $calculatedTax,
            'taxableIncome' => $taxableIncome,
            'mandatoryInsuranceDeductions' => $mandatoryInsuranceDeductions,
            'numDependents' => $numDependents,
            'taxCalculatorService' => $this->taxCalculatorService,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'personalDeductionAmount' => $personalDeductionAmount, // Truyền biến mới
            'dependentDeductionAmount' => $dependentDeductionAmount, // Truyền biến mới
        ]);
    }
}