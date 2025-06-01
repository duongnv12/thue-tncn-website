<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Thống kê Thuế TNCN') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">

                <h3 class="text-3xl font-bold text-center text-indigo-700 mb-8">Tổng quan Thuế Thu nhập Cá nhân</h3>

                {{-- Thông báo (nếu có) --}}
                @if(session('status'))
                    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
                        <p>{{ session('status') }}</p>
                    </div>
                @endif

                <div class="bg-gray-50 p-6 rounded-lg shadow-sm mb-8">
                    <h4 class="text-xl font-semibold text-gray-800 mb-4">Chọn năm xem thống kê</h4>
                    <form action="{{ route('tax_declarations.statistics') }}" method="GET" class="flex items-center space-x-4">
                        <label for="year" class="sr-only">Chọn năm:</label>
                        <select name="year" id="year" class="form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-gray-700">
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}" @if($year == $selectedYear) selected @endif>{{ $year }}</option>
                            @endforeach
                        </select>
                        <x-primary-button type="submit">
                            {{ __('Xem thống kê') }}
                        </x-primary-button>
                    </form>
                </div>

                <hr class="my-8 border-t-2 border-gray-200">

                <h4 class="text-2xl font-bold text-indigo-700 mb-6">Thống kê Thuế TNCN Hàng Năm</h4>

                @if($annualData->isEmpty())
                    <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-700 p-4 rounded" role="alert">
                        <p>Chưa có dữ liệu thống kê hàng năm.</p>
                    </div>
                @else
                    <div class="overflow-x-auto mb-10 shadow-md rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-indigo-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-indigo-800 uppercase tracking-wider">Năm</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-indigo-800 uppercase tracking-wider">Tổng thu nhập năm (Gross)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-indigo-800 uppercase tracking-wider">Tổng thuế TNCN phải nộp</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($annualData as $year => $data)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $year }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ number_format($data['total_income_year'], 0, ',', '.') }} VNĐ</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-semibold">{{ number_format($data['total_tax_year'], 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <hr class="my-8 border-t-2 border-gray-200">

                <h4 class="text-2xl font-bold text-indigo-700 mb-6">Thống kê Thuế TNCN Hàng Tháng (Năm {{ $selectedYear }})</h4>

                @if($fullMonthlyData->isEmpty() || ($fullMonthlyData->sum('total_income_month') == 0 && $fullMonthlyData->sum('total_tax_month') == 0))
                    <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-700 p-4 rounded" role="alert">
                        <p>Chưa có dữ liệu thống kê hàng tháng cho năm **{{ $selectedYear }}**.</p>
                    </div>
                @else
                    <div class="overflow-x-auto shadow-md rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-indigo-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-indigo-800 uppercase tracking-wider">Tháng</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-indigo-800 uppercase tracking-wider">Tổng thu nhập tháng (Gross)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-indigo-800 uppercase tracking-wider">Tổng thuế TNCN tháng</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($fullMonthlyData as $monthData)
                                    <tr class="hover:bg-gray-50">
                                        {{-- Định dạng tháng in hoa --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <strong>{{ Str::upper($monthData['month']) }}</strong>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ number_format($monthData['total_income_month'], 0, ',', '.') }} VNĐ</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-semibold">{{ number_format($monthData['total_tax_month'], 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>