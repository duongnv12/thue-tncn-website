<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chi tiết Khai báo Thuế') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Chi tiết Quyết toán Thuế TNCN Năm {{ $taxDeclaration->declaration_year }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <p class="text-sm text-gray-600">Tổng thu nhập gộp (chịu thuế):</p>
                            <p class="text-lg font-semibold text-gray-900">{{ number_format($taxDeclaration->total_gross_income, 0, ',', '.') }} VND</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tổng giảm trừ gia cảnh:</p>
                            <p class="text-lg font-semibold text-gray-900">{{ number_format($taxDeclaration->total_deductions, 0, ',', '.') }} VND</p>
                            <ul class="text-sm text-gray-600 ml-4">
                                <li>Giảm trừ bản thân: {{ number_format($taxDeclaration->personal_deduction, 0, ',', '.') }} VND</li>
                                <li>Giảm trừ người phụ thuộc: {{ number_format($taxDeclaration->dependent_deduction, 0, ',', '.') }} VND</li>
                                <li>Giảm trừ bảo hiểm: {{ number_format($taxDeclaration->insurance_deduction, 0, ',', '.') }} VND</li>
                                <li>Giảm trừ từ thiện: {{ number_format($taxDeclaration->charitable_deduction, 0, ',', '.') }} VND</li>
                            </ul>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Thu nhập tính thuế (sau giảm trừ):</p>
                            <p class="text-lg font-semibold text-gray-900">{{ number_format($taxDeclaration->tax_base_income, 0, ',', '.') }} VND</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tổng số thuế phải nộp trong năm:</p>
                            <p class="text-2xl font-bold text-green-700">{{ number_format($taxDeclaration->total_tax_payable, 0, ',', '.') }} VND</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tổng số thuế đã khấu trừ tại nguồn:</p>
                            <p class="text-lg font-semibold text-gray-900">{{ number_format($taxDeclaration->total_tax_withheld, 0, ',', '.') }} VND</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Chênh lệch thuế (Nộp thêm / Hoàn lại):</p>
                            @if ($taxDeclaration->tax_difference > 0)
                                <p class="text-2xl font-bold text-red-700">{{ number_format($taxDeclaration->tax_difference, 0, ',', '.') }} VND (Phải nộp thêm)</p>
                            @elseif ($taxDeclaration->tax_difference < 0)
                                <p class="text-2xl font-bold text-blue-700">{{ number_format(abs($taxDeclaration->tax_difference), 0, ',', '.') }} VND (Được hoàn lại)</p>
                            @else
                                <p class="text-2xl font-bold text-gray-700">{{ number_format($taxDeclaration->tax_difference, 0, ',', '.') }} VND (Không có chênh lệch)</p>
                            @endif
                        </div>
                    </div>

                    {{-- Hiển thị chi tiết các nguồn thu nhập nếu có --}}
                    @if (!empty($details))
                        <h4 class="text-md font-medium text-gray-900 mb-2 mt-6">Chi tiết các nguồn thu nhập được sử dụng:</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nguồn</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thu nhập chịu thuế</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thuế đã khấu trừ</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($details as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['source_name'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">{{ str_replace('_', ' ', $item['type']) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($item['total_taxable_income'], 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($item['tax_withheld'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-600 mt-6">Không có thông tin chi tiết nguồn thu nhập được lưu trữ cho bản khai báo này.</p>
                    @endif

                    <div class="mt-8 flex justify-end">
                        <a href="{{ route('tax_declarations.pdf', $taxDeclaration) }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-4">
                            {{ __('Xuất PDF') }}
                        </a>
                        <a href="{{ route('tax_declarations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Quay lại lịch sử khai báo') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>