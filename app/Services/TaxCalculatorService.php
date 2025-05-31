<?php

namespace App\Services;

use App\Models\User;
use App\Models\TaxDeclaration;
use Carbon\Carbon;

class TaxCalculatorService
{
    // Mức giảm trừ gia cảnh theo quy định hiện hành (áp dụng từ 01/07/2020)
    const PERSONAL_DEDUCTION_MONTHLY = 11000000; // 11 triệu/tháng
    const DEPENDENT_DEDUCTION_MONTHLY = 4400000; // 4.4 triệu/tháng

    const TAX_BRACKETS = [
        5000000 * 12 => 0.05,
        10000000 * 12 => 0.10,
        18000000 * 12 => 0.15,
        32000000 * 12 => 0.20,
        52000000 * 12 => 0.25,
        80000000 * 12 => 0.30,
        INF => 0.35,
    ];

    /**
     * Tính toán thuế TNCN cho một người dùng trong một năm cụ thể.
     *
     * @param User $user
     * @param int $year
     * @param float $providedInsuranceDeduction Tổng các khoản đóng góp bảo hiểm bắt buộc trong năm (nếu có)
     * @param float $providedCharitableDeduction Tổng các khoản đóng góp từ thiện, nhân đạo, khuyến học trong năm (nếu có)
     * @return array
     */
    public function calculateTax(
        User $user,
        int $year,
        float $providedInsuranceDeduction = 0,
        float $providedCharitableDeduction = 0
    ): array
    {
        // 1. Tổng hợp Thu nhập chịu thuế từ tất cả các nguồn
        $totalGrossIncome = $user->incomeSources()
                                 ->where('year', $year)
                                 ->sum('total_taxable_income');

        // 2. Tổng số thuế đã khấu trừ tại nguồn
        $totalTaxWithheld = $user->incomeSources()
                                 ->where('year', $year)
                                 ->sum('tax_withheld');

        // 3. Tính các khoản giảm trừ
        $personalDeduction = self::PERSONAL_DEDUCTION_MONTHLY * 12; // Giảm trừ bản thân (cả năm)

        $dependentDeduction = 0;
        foreach ($user->dependents as $dependent) {
            // Giảm trừ người phụ thuộc chỉ tính cho các tháng đăng ký
            $dependentDeduction += self::DEPENDENT_DEDUCTION_MONTHLY * $dependent->months_registered;
        }

        // Sử dụng các giá trị giảm trừ bổ sung được cung cấp từ form
        $insuranceDeduction = $providedInsuranceDeduction;
        $charitableDeduction = $providedCharitableDeduction;

        $totalDeductions = $personalDeduction + $dependentDeduction + $insuranceDeduction + $charitableDeduction;

        // 4. Tính Thu nhập tính thuế (Tax Base Income)
        $taxBaseIncome = $totalGrossIncome - $totalDeductions;
        if ($taxBaseIncome < 0) {
            $taxBaseIncome = 0;
        }

        // 5. Tính tổng số thuế phải nộp trong năm (theo biểu lũy tiến)
        $totalTaxPayable = $this->calculateProgressiveTax($taxBaseIncome);

        // 6. Tính chênh lệch thuế (nộp thêm/hoàn lại)
        $taxDifference = $totalTaxPayable - $totalTaxWithheld;

        return [
            'declaration_year' => $year,
            'total_gross_income' => $totalGrossIncome,
            'total_taxable_income' => $totalGrossIncome,
            'total_deductions' => $totalDeductions,
            'personal_deduction' => $personalDeduction,
            'dependent_deduction' => $dependentDeduction,
            'insurance_deduction' => $insuranceDeduction, // Lưu giá trị đã nhập
            'charitable_deduction' => $charitableDeduction, // Lưu giá trị đã nhập
            'tax_base_income' => $taxBaseIncome,
            'total_tax_payable' => $totalTaxPayable,
            'total_tax_withheld' => $totalTaxWithheld,
            'tax_difference' => $taxDifference,
            'status' => 'calculated',
            'details_json' => json_encode($user->incomeSources()->where('year', $year)->get()->toArray()),
        ];
    }

    /**
     * Hàm tính thuế theo biểu lũy tiến từng phần (cho thu nhập cả năm).
     *
     * @param float $income Thu nhập tính thuế (cả năm)
     * @return float Tổng số thuế phải nộp
     */
    private function calculateProgressiveTax(float $income): float
    {
        $tax = 0;
        $remainingIncome = $income;
        $previousBracketUpper = 0; // Cận trên của bậc trước đó

        foreach (self::TAX_BRACKETS as $upperBound => $rate) {
            if ($remainingIncome <= 0) {
                break; // Không còn thu nhập để tính thuế
            }

            $bracketAmount = $upperBound - $previousBracketUpper; // Lượng thu nhập trong bậc hiện tại

            if ($remainingIncome > $bracketAmount) {
                // Nếu thu nhập còn lại lớn hơn ngưỡng của bậc hiện tại, tính hết cho bậc này
                $tax += $bracketAmount * $rate;
                $remainingIncome -= $bracketAmount;
            } else {
                // Nếu thu nhập còn lại nhỏ hơn hoặc bằng ngưỡng, tính phần còn lại và thoát
                $tax += $remainingIncome * $rate;
                $remainingIncome = 0;
            }
            $previousBracketUpper = $upperBound;
        }

        return $tax;
    }

    /**
     * Lưu kết quả tính toán vào bảng tax_declarations.
     *
     * @param User $user
     * @param array $calculationResult
     * @return TaxDeclaration
     */
    public function saveDeclaration(User $user, array $calculationResult): TaxDeclaration
    {
        // Kiểm tra xem đã có bản ghi cho user và năm này với status 'calculated' chưa
        // Để tránh trùng lặp khi tính toán lại
        $existingDeclaration = $user->taxDeclarations()
                                   ->where('declaration_year', $calculationResult['declaration_year'])
                                   ->first();

        if ($existingDeclaration) {
            // Cập nhật bản ghi nếu đã tồn tại
            $existingDeclaration->update($calculationResult);
            return $existingDeclaration;
        } else {
            // Tạo mới nếu chưa có
            return $user->taxDeclarations()->create($calculationResult);
        }
    }
}