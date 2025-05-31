<?php

namespace App\Services;

use App\Models\TaxSetting;
use App\Models\TaxBracket;
use App\Models\TaxDeclaration;
use App\Models\User; // Thêm để dùng cho thống kê người dùng

class TaxCalculatorService
{
    /**
     * Lấy mức giảm trừ bản thân từ cài đặt hệ thống.
     *
     * @return float
     */
    public function getPersonalDeduction(): float
    {
        return TaxSetting::where('setting_key', 'personal_deduction')->value('setting_value') ?? 0;
    }

    /**
     * Lấy mức giảm trừ cho mỗi người phụ thuộc từ cài đặt hệ thống.
     *
     * @return float
     */
    public function getDependentDeduction(): float
    {
        return TaxSetting::where('setting_key', 'dependent_deduction')->value('setting_value') ?? 0;
    }

    /**
     * Lấy tất cả các bậc thuế từ cài đặt hệ thống.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTaxBrackets()
    {
        return TaxBracket::orderBy('bracket_number')->get();
    }

    /**
     * Tính tổng số tiền giảm trừ gia cảnh.
     *
     * @param int $numDependents Số lượng người phụ thuộc.
     * @return float
     */
    public function calculateTotalDeductions(int $numDependents): float
    {
        $personalDeduction = $this->getPersonalDeduction();
        $dependentDeduction = $this->getDependentDeduction();

        return $personalDeduction + ($numDependents * $dependentDeduction);
    }

    /**
     * Tính thu nhập chịu thuế.
     *
     * @param float $totalIncome Tổng thu nhập từ tiền lương, tiền công.
     * @param int $numDependents Số lượng người phụ thuộc.
     * @return float Thu nhập tính thuế (luôn >= 0).
     */
    public function calculateTaxableIncome(float $totalIncome, int $numDependents): float
    {
        $totalDeductions = $this->calculateTotalDeductions($numDependents);
        $taxableIncome = $totalIncome - $totalDeductions;

        return max(0, $taxableIncome); // Thu nhập tính thuế không bao giờ âm
    }

    /**
     * Tính thuế thu nhập cá nhân phải nộp dựa trên biểu thuế lũy tiến từng phần.
     *
     * @param float $taxableIncome Thu nhập tính thuế.
     * @return float Thuế TNCN phải nộp.
     */
    public function calculatePIT(float $taxableIncome): float
    {
        if ($taxableIncome <= 0) {
            return 0;
        }

        $taxBrackets = $this->getTaxBrackets();
        $calculatedTax = 0;
        $remainingTaxableIncome = $taxableIncome;

        foreach ($taxBrackets as $bracket) {
            // Xác định giới hạn trên của bậc thuế hiện tại
            // Nếu là bậc cuối cùng (max_income là null), giới hạn trên là phần thu nhập còn lại
            $upperLimit = $bracket->max_income ?? $remainingTaxableIncome;

            // Tính toán phần thu nhập trong bậc hiện tại
            $incomeInBracket = min($remainingTaxableIncome, $upperLimit - $bracket->min_income);

            // Đảm bảo không tính thuế cho phần thu nhập dưới min_income của bậc hiện tại
            if ($remainingTaxableIncome > $bracket->min_income) {
                 $incomeToTaxThisBracket = min($remainingTaxableIncome - $bracket->min_income, $bracket->max_income !== null ? ($bracket->max_income - $bracket->min_income) : $remainingTaxableIncome);
                 $calculatedTax += ($incomeToTaxThisBracket * ($bracket->tax_rate / 100));
            }


            // Cập nhật phần thu nhập còn lại
            $remainingTaxableIncome -= $incomeInBracket;

            if ($remainingTaxableIncome <= 0 && $bracket->max_income !== null) {
                break; // Đã xử lý hết thu nhập hoặc đến bậc có max_income rõ ràng
            }
        }
        // Công thức tính lũy tiến cần được chỉnh sửa chính xác hơn
        // Cách đúng là:
        // Với thu nhập tính thuế T, và các bậc [0, B1], (B1, B2], (B2, B3]...
        // Thuế = (min(T, B1) - 0) * R1 + (min(T, B2) - B1) * R2 + ...
        // Hoặc sử dụng phương pháp rút gọn (đơn giản hơn trong code)
        // Ví dụ: Thuế = T * % - Giảm trừ theo bậc
        // Chúng ta sẽ dùng phương pháp trực tiếp để dễ hiểu và đúng với định nghĩa ban đầu

        $calculatedTax = 0;
        $currentTaxableIncome = $taxableIncome;

        foreach ($taxBrackets as $bracket) {
            if ($currentTaxableIncome <= 0) {
                break;
            }

            // Khoảng thu nhập của bậc hiện tại
            $bracketRange = $bracket->max_income !== null ? ($bracket->max_income - $bracket->min_income) : PHP_INT_MAX; // Giới hạn cuối cùng

            // Phần thu nhập nằm trong bậc hiện tại
            $incomeInThisBracket = min($currentTaxableIncome - $bracket->min_income, $bracketRange);

            if ($incomeInThisBracket > 0) {
                $calculatedTax += $incomeInThisBracket * ($bracket->tax_rate / 100);
            }
            $currentTaxableIncome -= $incomeInThisBracket;
        }

        return round($calculatedTax, 0); // Làm tròn đến số nguyên
    }

    /**
     * Lưu thông tin khai báo thuế vào cơ sở dữ liệu.
     *
     * @param array $data Dữ liệu khai báo bao gồm user_id, total_income, total_deductions, taxable_income, calculated_tax, declaration_month, declaration_year.
     * @return TaxDeclaration
     */
    public function saveDeclaration(array $data): TaxDeclaration
    {
        return TaxDeclaration::create($data);
    }

    /**
     * Lấy thống kê thuế hàng năm cho một người dùng cụ thể.
     *
     * @param int $userId ID của người dùng.
     * @return \Illuminate\Support\Collection
     */
    public function getYearlyTaxStatistics(int $userId)
    {
        // Lấy dữ liệu tổng hợp theo năm và tháng
        $rawStats = TaxDeclaration::where('user_id', $userId)
            ->selectRaw('
                declaration_year,
                declaration_month,
                SUM(total_income) as total_income,
                SUM(calculated_tax) as total_tax
            ')
            ->groupBy('declaration_year', 'declaration_month')
            ->orderBy('declaration_year', 'asc')
            ->orderBy('declaration_month', 'asc')
            ->get();

        // Chuyển đổi thành dạng dễ sử dụng cho biểu đồ/bảng
        $yearlyStats = [];
        foreach ($rawStats as $stat) {
            if (!isset($yearlyStats[$stat->declaration_year])) {
                $yearlyStats[$stat->declaration_year] = [
                    'year' => $stat->declaration_year,
                    'total_income' => 0,
                    'total_tax' => 0,
                    'monthly_data' => array_fill(1, 12, ['income' => 0, 'tax' => 0]) // Khởi tạo dữ liệu 12 tháng
                ];
            }
            $yearlyStats[$stat->declaration_year]['total_income'] += $stat->total_income;
            $yearlyStats[$stat->declaration_year]['total_tax'] += $stat->total_tax;
            $yearlyStats[$stat->declaration_year]['monthly_data'][$stat->declaration_month] = [
                'income' => $stat->total_income,
                'tax' => $stat->total_tax
            ];
        }

        return collect($yearlyStats)->sortBy('year');
    }
}