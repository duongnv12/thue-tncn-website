@php
    use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khai báo Thuế TNCN Tháng {{ $declaration->declaration_month }}/{{ $declaration->declaration_year }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif; /* Hoặc 'Arial', 'Times New Roman' nếu bạn đã cấu hình font */
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        h1, h2, h3, h4, h5, h6 {
            color: #1a202c;
            margin-top: 0;
            margin-bottom: 10px;
        }
        h3 { font-size: 20px; text-align: center; margin-bottom: 20px; }
        h4 { font-size: 18px; color: #4338ca; }
        h5 { font-size: 16px; color: #1a202c; margin-top: 20px; }
        strong { font-weight: bold; }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .section {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f8fafc;
        }
        .section h6 {
            font-size: 15px;
            color: #4c51bf;
            margin-bottom: 10px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        .grid-item {
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .bg-indigo-50 { background-color: #eef2ff; }
        .bg-green-50 { background-color: #f0fdf4; }
        .bg-blue-50 { background-color: #eff6ff; }
        .bg-purple-50 { background-color: #f5f3ff; }
        .bg-yellow-50 { background-color: #fffbeb; }
        .bg-red-50 { background-color: #fef2f2; }
        .text-indigo-800 { color: #3730a3; }
        .text-green-800 { color: #166534; }
        .text-blue-700 { color: #1d4ed8; }
        .text-purple-700 { color: #6d28d9; }
        .text-yellow-700 { color: #b45309; }
        .text-red-700 { color: #b91c1c; }
        .text-red-600 { color: #dc2626; }
        .text-red-800 { color: #991b1b; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #f7fafc;
            font-weight: bold;
        }
        ul {
            list-style: disc;
            padding-left: 20px;
            margin-top: 10px;
        }
        ul li {
            margin-bottom: 5px;
        }
        .text-center { text-align: center; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; }
        .mb-3 { margin-bottom: 12px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-4 { margin-bottom: 16px; }
        .mb-6 { margin-bottom: 24px; }
        .mb-8 { margin-bottom: 32px; }
        .p-4 { padding: 16px; }
        .p-5 { padding: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>BÁO CÁO TÍNH THUẾ THU NHẬP CÁ NHÂN</h3>
            <h2>Tháng {{ $declaration->declaration_month }}/{{ $declaration->declaration_year }}</h2>
            <p><strong>Người khai báo:</strong> {{ $user->name }} (ID: {{ $user->id }})</p>
            <p>Ngày xuất báo cáo: {{ Carbon::now()->format('d/m/Y H:i') }}</p>
        </div>

        <hr style="border-top: 1px dashed #ccc; margin-bottom: 20px;">

        <div class="grid">
            <div class="grid-item bg-indigo-50">
                <p>Tổng thu nhập (Gross):</p>
                <p style="font-size: 24px; font-weight: bold; color: #3730a3;">{{ number_format($grossIncome, 0, ',', '.') }} VNĐ</p>
            </div>
            <div class="grid-item bg-green-50">
                <p>Lương thực nhận (Net):</p>
                <p style="font-size: 24px; font-weight: bold; color: #166534;">{{ number_format($grossIncome - $mandatoryInsuranceDeductions - $calculatedTax, 0, ',', '.') }} VNĐ</p>
            </div>
        </div>

        <h5 class="text-xl font-semibold mb-4 text-gray-800">Chi tiết các khoản tính toán:</h5>

        <div class="section bg-blue-50">
            <h6>1. Các khoản Bảo hiểm bắt buộc (Giảm trừ):</h6>
            <p class="mb-2">Mức đóng bảo hiểm được tính trên <strong>{{ number_format($grossIncome, 0, ',', '.') }} VNĐ</strong> (hoặc mức trần bảo hiểm tại thời điểm khai báo).</p>
            <ul>
                <li>Bảo hiểm xã hội (BHXH - {{ number_format($taxCalculatorService->getTaxSetting('bhxh_employee_rate') * 100, 2) }}%): <strong>{{ number_format($taxCalculatorService->calculateBhxhDeduction($grossIncome), 0, ',', '.') }} VNĐ</strong></li>
                <li>Bảo hiểm y tế (BHYT - {{ number_format($taxCalculatorService->getTaxSetting('bhyt_employee_rate') * 100, 2) }}%): <strong>{{ number_format($taxCalculatorService->calculateBhytDeduction($grossIncome), 0, ',', '.') }} VNĐ</strong></li>
                <li>Bảo hiểm thất nghiệp (BHTN - {{ number_format($taxCalculatorService->getTaxSetting('bhtn_employee_rate') * 100, 2) }}%): <strong>{{ number_format($taxCalculatorService->calculateBhtnDeduction($grossIncome), 0, ',', '.') }} VNĐ</strong></li>
            </ul>
            <p class="mt-3 font-semibold">Tổng cộng bảo hiểm bắt buộc: <strong>{{ number_format($mandatoryInsuranceDeductions, 0, ',', '.') }} VNĐ</strong></p>
        </div>

        <div class="section bg-purple-50">
            <h6>2. Các khoản Giảm trừ gia cảnh:</h6>
            <ul>
                <li>Giảm trừ bản thân: <strong>{{ number_format($personalDeductionAmount, 0, ',', '.') }} VNĐ</strong></li>
                <li>Giảm trừ người phụ thuộc ({{ $numDependents }} người x {{ number_format($taxCalculatorService->getDependentDeduction(), 0, ',', '.') }} VNĐ/người): <strong>{{ number_format($dependentDeductionAmount, 0, ',', '.') }} VNĐ</strong></li>
            </ul>
            <p class="mt-3 font-semibold">Tổng cộng giảm trừ gia cảnh: <strong>{{ number_format($personalDeductionAmount + $dependentDeductionAmount, 0, ',', '.') }} VNĐ</strong></p>
        </div>

        <div class="section bg-yellow-50">
            <h6>3. Thu nhập tính thuế:</h6>
            <p>
                Thu nhập tính thuế = Tổng thu nhập Gross - Tổng bảo hiểm bắt buộc - Tổng giảm trừ gia cảnh
            </p>
            <p class="mt-2 font-semibold" style="font-size: 16px; color: #b45309;">
                = {{ number_format($grossIncome, 0, ',', '.') }} - {{ number_format($mandatoryInsuranceDeductions, 0, ',', '.') }} - {{ number_format($personalDeductionAmount + $dependentDeductionAmount, 0, ',', '.') }}
                = <strong>{{ number_format($taxableIncome, 0, ',', '.') }} VNĐ</strong>
            </p>
        </div>

        <div class="section bg-red-50">
            <h6>4. Thuế Thu nhập cá nhân (PIT) phải nộp theo biểu lũy tiến:</h6>
            <p class="mb-3">Thu nhập tính thuế <strong>{{ number_format($taxableIncome, 0, ',', '.') }} VNĐ</strong> sẽ được tính theo các bậc sau:</p>
            <table>
                <thead>
                    <tr>
                        <th>Bậc thuế</th>
                        <th>Phần thu nhập tính thuế</th>
                        <th>Thuế suất</th>
                        <th>Số thuế phải nộp</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $remainingTaxable = $taxableIncome;
                        $taxBrackets = $taxCalculatorService->getTaxBrackets();
                    @endphp

                    @foreach ($taxBrackets as $index => $bracket)
                        @if ($remainingTaxable <= 0)
                            @break
                        @endif

                        @php
                            $prevUpper = ($index > 0) ? $taxBrackets[$index-1]->max_income : 0;
                            $bracketAmount = ($bracket->max_income !== null) ? ($bracket->max_income - $prevUpper) : $remainingTaxable;
                            $taxableInBracket = min($remainingTaxable, $bracketAmount);
                            $taxAmount = $taxableInBracket * ($bracket->rate / 100);
                            $remainingTaxable -= $taxableInBracket;
                        @endphp
                        <tr>
                            <td>Bậc {{ $index + 1 }}</td>
                            <td>
                                @if ($bracket->max_income === null)
                                    Trên {{ number_format($prevUpper, 0, ',', '.') }} VNĐ
                                @else
                                    Từ {{ number_format($prevUpper + 1, 0, ',', '.') }} VNĐ đến {{ number_format($bracket->max_income, 0, ',', '.') }} VNĐ
                                @endif
                                <span style="display: block; font-size: 10px; color: #718096;">(Áp dụng cho: {{ number_format($taxableInBracket, 0, ',', '.') }} VNĐ)</span>
                            </td>
                            <td>{{ number_format($bracket->rate, 0) }}%</td>
                            <td style="color: #dc2626; font-weight: bold;">{{ number_format($taxAmount, 0, ',', '.') }} VNĐ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="mt-3 font-semibold" style="font-size: 16px; color: #991b1b;">Tổng Thuế TNCN phải nộp: <strong>{{ number_format($calculatedTax, 0, ',', '.') }} VNĐ</strong></p>
        </div>
    </div>
</body>
</html>