<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Thống kê Thuế Thu nhập Cá nhân của bạn') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-6">Thống kê tổng thu nhập và tổng thuế phải nộp của bạn theo từng năm, dựa trên các khai báo đã lưu.</p>

                    @if ($yearlyStats->isEmpty())
                        <p>Bạn chưa có dữ liệu thống kê. Hãy lưu một số khai báo thuế để xem thống kê!</p>
                        <a href="{{ route('tax_calculation.index') }}" class="text-blue-600 hover:underline mt-4 block">Đi đến trang Tính Thuế TNCN</a>
                    @else
                        @foreach ($yearlyStats as $yearData)
                            <div class="mb-8 border-b pb-4">
                                <h3 class="font-semibold text-2xl text-gray-800 mb-4">Năm {{ $yearData['year'] }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    <div class="bg-indigo-50 p-4 rounded-lg shadow-sm">
                                        <p class="text-lg font-medium text-indigo-800">Tổng thu nhập năm:</p>
                                        <p class="text-3xl font-bold text-indigo-900">{{ number_format($yearData['total_income'], 0, ',', '.') }} VNĐ</p>
                                    </div>
                                    <div class="bg-red-50 p-4 rounded-lg shadow-sm">
                                        <p class="text-lg font-medium text-red-800">Tổng thuế phải nộp năm:</p>
                                        <p class="text-3xl font-bold text-red-900">{{ number_format($yearData['total_tax'], 0, ',', '.') }} VNĐ</p>
                                    </div>
                                </div>

                                <h4 class="font-semibold text-lg text-gray-700 mb-3">Chi tiết theo tháng:</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full leading-normal bg-white border border-gray-200 rounded-lg">
                                        <thead>
                                            <tr>
                                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tháng</th>
                                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tổng thu nhập (tháng)</th>
                                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Thuế phải nộp (tháng)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <tr class="{{ $i % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                                                    <td class="px-5 py-3 border-b border-gray-200 text-sm">{{ $i }}</td>
                                                    <td class="px-5 py-3 border-b border-gray-200 text-sm">
                                                        {{ number_format($yearData['monthly_data'][$i]['income'], 0, ',', '.') }} VNĐ
                                                    </td>
                                                    <td class="px-5 py-3 border-b border-gray-200 text-sm">
                                                        {{ number_format($yearData['monthly_data'][$i]['tax'], 0, ',', '.') }} VNĐ
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>