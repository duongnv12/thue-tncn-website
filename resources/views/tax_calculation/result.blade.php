<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kết quả Tính toán Thuế TNCN') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Kết quả Quyết toán Thuế TNCN Năm {{ $calculationResult['declaration_year'] }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Tổng thu nhập gộp (chịu thuế):</p>
                            <p class="text-lg font-semibold text-gray-900">{{ number_format($calculationResult['total_gross_income'], 0, ',', '.') }} VND</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tổng giảm trừ gia cảnh:</p>
                            <p class="text-lg font-semibold text-gray-900">{{ number_format($calculationResult['total_deductions'], 0, ',', '.') }} VND</p>
                            <ul class="text-sm text-gray-600 ml-4">
                                <li>Giảm trừ bản thân: {{ number_format($calculationResult['personal_deduction'], 0, ',', '.') }} VND</li>
                                <li>Giảm trừ người phụ thuộc: {{ number_format($calculationResult['dependent_deduction'], 0, ',', '.') }} VND</li>
                                <li>Giảm trừ bảo hiểm: {{ number_format($calculationResult['insurance_deduction'], 0, ',', '.') }} VND</li> 
                                <li>Giảm trừ từ thiện: {{ number_format($calculationResult['charitable_deduction'], 0, ',', '.') }} VND</li>
                            </ul>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Thu nhập tính thuế (sau giảm trừ):</p>
                            <p class="text-lg font-semibold text-gray-900">{{ number_format($calculationResult['tax_base_income'], 0, ',', '.') }} VND</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tổng số thuế phải nộp trong năm:</p>
                            <p class="text-2xl font-bold text-green-700">{{ number_format($calculationResult['total_tax_payable'], 0, ',', '.') }} VND</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tổng số thuế đã khấu trừ tại nguồn:</p>
                            <p class="text-lg font-semibold text-gray-900">{{ number_format($calculationResult['total_tax_withheld'], 0, ',', '.') }} VND</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Chênh lệch thuế (Nộp thêm / Hoàn lại):</p>
                            @if ($calculationResult['tax_difference'] > 0)
                                <p class="text-2xl font-bold text-red-700">{{ number_format($calculationResult['tax_difference'], 0, ',', '.') }} VND (Phải nộp thêm)</p>
                            @elseif ($calculationResult['tax_difference'] < 0)
                                <p class="text-2xl font-bold text-blue-700">{{ number_format(abs($calculationResult['tax_difference']), 0, ',', '.') }} VND (Được hoàn lại)</p>
                            @else
                                <p class="text-2xl font-bold text-gray-700">{{ number_format($calculationResult['tax_difference'], 0, ',', '.') }} VND (Không có chênh lệch)</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <a href="{{ route('tax_declarations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Xem các bản khai báo khác') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>