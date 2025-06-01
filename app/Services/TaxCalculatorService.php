<?php

namespace App\Services;

use App\Models\TaxSetting;
use App\Models\TaxBracket;
use Carbon\Carbon;

class TaxCalculatorService
{
    protected $settings = [];

    public function __construct()
    {
        $this->loadTaxSettings();
    }

    protected function loadTaxSettings()
    {
        $allSettings = TaxSetting::all();
        foreach ($allSettings as $setting) {
            $this->settings[$setting->key] = $setting->value;
        }
    }

    public function getTaxSetting($key)
    {
        return $this->settings[$key] ?? null;
    }

    public function getPersonalDeduction()
    {
        return (float) $this->getTaxSetting('personal_deduction');
    }

    public function getDependentDeduction()
    {
        return (float) $this->getTaxSetting('dependent_deduction');
    }

    public function getRegionalMinimumWage()
    {
        return (float) $this->getTaxSetting('regional_minimum_wage');
    }

    public function calculateMandatoryInsuranceDeductions(float $grossIncome)
    {
        $bhxh = $this->calculateBhxhDeduction($grossIncome);
        $bhyt = $this->calculateBhytDeduction($grossIncome);
        $bhtn = $this->calculateBhtnDeduction($grossIncome);

        return $bhxh + $bhyt + $bhtn;
    }

    public function calculateBhxhDeduction(float $grossIncome)
    {
        $rate = (float) $this->getTaxSetting('bhxh_employee_rate') / 100;
        $insuranceCap = (float) $this->getTaxSetting('insurance_base_cap');
        $incomeForBhxh = min($grossIncome, $insuranceCap);
        return $incomeForBhxh * $rate;
    }

    public function calculateBhytDeduction(float $grossIncome)
    {
        $rate = (float) $this->getTaxSetting('bhyt_employee_rate') / 100;
        $insuranceCap = (float) $this->getTaxSetting('insurance_base_cap');
        $incomeForBhyt = min($grossIncome, $insuranceCap);
        return $incomeForBhyt * $rate;
    }

    public function calculateBhtnDeduction(float $grossIncome)
    {
        $rate = (float) $this->getTaxSetting('bhtn_employee_rate') / 100;
        $regionalMinimumWage = $this->getRegionalMinimumWage();
        $bhtnCap = $regionalMinimumWage * 20;

        $incomeForBhtn = min($grossIncome, $bhtnCap);
        return $incomeForBhtn * $rate;
    }

    public function calculateTotalDeductions(float $grossIncome, int $numDependents)
    {
        $mandatoryInsurance = $this->calculateMandatoryInsuranceDeductions($grossIncome);
        $personalDeduction = $this->getPersonalDeduction();
        $dependentDeduction = $this->getDependentDeduction() * $numDependents;

        return $mandatoryInsurance + $personalDeduction + $dependentDeduction;
    }

    public function calculateTaxableIncome(float $grossIncome, int $numDependents)
    {
        $totalDeductions = $this->calculateTotalDeductions($grossIncome, $numDependents);
        return max(0, $grossIncome - $totalDeductions);
    }

    public function calculatePIT(float $taxableIncome)
    {
        if ($taxableIncome <= 0) {
            return 0;
        }

        $totalPit = 0;
        $remainingTaxable = $taxableIncome;
        $taxBrackets = $this->getTaxBrackets();

        foreach ($taxBrackets as $index => $bracket) {
            if ($remainingTaxable <= 0) {
                break;
            }

            // DÙNG CỘT THỰC TẾ TRONG DATABASE CỦA BẠN
            // Giả sử cột là 'min_income' và 'max_income'
            $prevUpperBound = ($index > 0) ? $taxBrackets[$index-1]->max_income : 0;
            $currentBracketUpperBound = $bracket->max_income;
            $rate = $bracket->rate / 100;

            $bracketLimit = ($currentBracketUpperBound !== null) ? ($currentBracketUpperBound - $prevUpperBound) : $remainingTaxable;
            $amountInBracket = min($remainingTaxable, $bracketLimit);

            $totalPit += $amountInBracket * $rate;
            $remainingTaxable -= $amountInBracket;
        }

        return max(0, $totalPit);
    }

    /**
     * Lấy tất cả các bậc thuế từ database, sắp xếp theo thứ tự.
     * CẬP NHẬT TÊN CỘT 'lower_bound' THÀNH TÊN CỘT THỰC TẾ CỦA BẠN (ví dụ: 'min_income')
     */
    public function getTaxBrackets()
    {
        return TaxBracket::orderBy('min_income')->get(); // <--- CHỈNH SỬA DÒNG NÀY
    }
}