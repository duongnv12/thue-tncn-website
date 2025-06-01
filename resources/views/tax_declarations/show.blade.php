<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chi tiết Khai báo Thuế') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">

                <h3 class="text-xl font-semibold mb-6">Chi tiết Khai báo Thuế tháng {{ $declaration->declaration_month }}/{{ $declaration->declaration_year }}</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="p-5 bg-indigo-50 rounded-lg shadow-sm">
                        <p class="text-lg font-medium text-gray-700">Tổng thu nhập (Gross):</p>
                        <p class="text-3xl font-extrabold text-indigo-800">{{ number_format($grossIncome, 0, ',', '.') }} VNĐ</p>
                    </div>
                    <div class="p-5 bg-green-50 rounded-lg shadow-sm">
                        <p class="text-lg font-medium text-gray-700">Lương thực nhận (Net):</p>
                        <p class="text-3xl font-extrabold text-green-800">{{ number_format($grossIncome - $mandatoryInsuranceDeductions - $calculatedTax, 0, ',', '.') }} VNĐ</p>
                    </div>
                </div>

                <h5 class="text-xl font-semibold mb-4 text-gray-800">Chi tiết các khoản tính toán:</h5>

                <div class="mb-6 p-4 border border-blue-200 rounded-lg bg-blue-50">
                    <h6 class="font-bold text-lg text-blue-700 mb-3">1. Các khoản Bảo hiểm bắt buộc (Giảm trừ):</h6>
                    <p class="mb-2 text-gray-700">Mức đóng bảo hiểm được tính trên <strong>{{ number_format($grossIncome, 0, ',', '.') }} VNĐ</strong> (hoặc mức trần bảo hiểm tại thời điểm khai báo).</p>
                    <ul class="list-disc list-inside text-gray-600">
                        <li>Bảo hiểm xã hội (BHXH - {{ number_format($taxCalculatorService->getTaxSetting('bhxh_employee_rate') * 100, 2) }}%): <strong>{{ number_format($taxCalculatorService->calculateBhxhDeduction($grossIncome), 0, ',', '.') }} VNĐ</strong></li>
                        <li>Bảo hiểm y tế (BHYT - {{ number_format($taxCalculatorService->getTaxSetting('bhyt_employee_rate') * 100, 2) }}%): <strong>{{ number_format($taxCalculatorService->calculateBhytDeduction($grossIncome), 0, ',', '.') }} VNĐ</strong></li>
                        <li>Bảo hiểm thất nghiệp (BHTN - {{ number_format($taxCalculatorService->getTaxSetting('bhtn_employee_rate') * 100, 2) }}%): <strong>{{ number_format($taxCalculatorService->calculateBhtnDeduction($grossIncome), 0, ',', '.') }} VNĐ</strong></li>
                    </ul>
                    <p class="mt-3 font-semibold text-gray-800">Tổng cộng bảo hiểm bắt buộc: <strong>{{ number_format($mandatoryInsuranceDeductions, 0, ',', '.') }} VNĐ</strong></p>
                </div>

                <div class="mb-6 p-4 border border-purple-200 rounded-lg bg-purple-50">
                    <h6 class="font-bold text-lg text-purple-700 mb-3">2. Các khoản Giảm trừ gia cảnh:</h6>
                    <ul class="list-disc list-inside text-gray-600">
                        <li>Giảm trừ bản thân: <strong>{{ number_format($personalDeductionAmount, 0, ',', '.') }} VNĐ</strong></li>
                        <li>Giảm trừ người phụ thuộc ({{ $numDependents }} người x {{ number_format($taxCalculatorService->getDependentDeduction(), 0, ',', '.') }} VNĐ/người): <strong>{{ number_format($dependentDeductionAmount, 0, ',', '.') }} VNĐ</strong></li>
                    </ul>
                    <p class="mt-3 font-semibold text-gray-800">Tổng cộng giảm trừ gia cảnh: <strong>{{ number_format($personalDeductionAmount + $dependentDeductionAmount, 0, ',', '.') }} VNĐ</strong></p>
                </div>

                <div class="mb-6 p-4 border border-yellow-200 rounded-lg bg-yellow-50">
                    <h6 class="font-bold text-lg text-yellow-700 mb-3">3. Thu nhập tính thuế:</h6>
                    <p class="text-gray-700">
                        Thu nhập tính thuế = Tổng thu nhập Gross - Tổng bảo hiểm bắt buộc - Tổng giảm trừ gia cảnh
                    </p>
                    <p class="mt-2 font-semibold text-yellow-800 text-xl">
                        = {{ number_format($grossIncome, 0, ',', '.') }} - {{ number_format($mandatoryInsuranceDeductions, 0, ',', '.') }} - {{ number_format($personalDeductionAmount + $dependentDeductionAmount, 0, ',', '.') }}
                        = <strong>{{ number_format($taxableIncome, 0, ',', '.') }} VNĐ</strong>
                    </p>
                </div>

                <div class="mb-6 p-4 border border-red-200 rounded-lg bg-red-50">
                    <h6 class="font-bold text-lg text-red-700 mb-3">4. Thuế Thu nhập cá nhân (PIT) phải nộp theo biểu lũy tiến:</h6>
                    <p class="mb-3 text-gray-700">Thu nhập tính thuế <strong>{{ number_format($taxableIncome, 0, ',', '.') }} VNĐ</strong> sẽ được tính theo các bậc sau:</p>
                    <table class="min-w-full divide-y divide-gray-200 mb-3">
                        <thead class="bg-red-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Bậc thuế</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Phần thu nhập tính thuế</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Thuế suất</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Số thuế phải nộp</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $remainingTaxable = $taxableIncome;
                                $totalPit = 0;
                                $taxBrackets = $taxCalculatorService->getTaxBrackets(); // Lấy các bậc thuế hiện hành
                            @endphp

                            @foreach ($taxBrackets as $index => $bracket)
                                @if ($remainingTaxable <= 0)
                                    @break
                                @endif

                                @php
                                    // Đảm bảo tên cột khớp với CSDL của bạn (ví dụ: min_income, max_income)
                                    $prevUpper = ($index > 0) ? $taxBrackets[$index-1]->max_income : 0;
                                    $bracketAmount = ($bracket->max_income !== null) ? ($bracket->max_income - $prevUpper) : $remainingTaxable;
                                    $taxableInBracket = min($remainingTaxable, $bracketAmount);
                                    $taxAmount = $taxableInBracket * ($bracket->rate / 100);
                                    $totalPit += $taxAmount;
                                    $remainingTaxable -= $taxableInBracket;
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Bậc {{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if ($bracket->max_income === null)
                                            Trên {{ number_format($prevUpper, 0, ',', '.') }} VNĐ
                                        @else
                                            Từ {{ number_format($prevUpper + 1, 0, ',', '.') }} VNĐ đến {{ number_format($bracket->max_income, 0, ',', '.') }} VNĐ
                                        @endif
                                        <span class="block text-xs text-gray-400">(Áp dụng cho: {{ number_format($taxableInBracket, 0, ',', '.') }} VNĐ)</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($bracket->rate, 0) }}%</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-semibold"><strong>{{ number_format($taxAmount, 0, ',', '.') }} VNĐ</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p class="mt-3 font-semibold text-red-800 text-xl">Tổng Thuế TNCN phải nộp: <strong>{{ number_format($calculatedTax, 0, ',', '.') }} VNĐ</strong></p>
                </div>

                <div class="mt-8 text-center">
                    <a href="{{ route('tax_declarations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Quay lại Lịch sử khai báo') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>