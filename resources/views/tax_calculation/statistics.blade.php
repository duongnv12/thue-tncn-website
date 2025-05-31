<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Thống kê Thuế Thu nhập cá nhân') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tổng quan thuế theo năm</h3>

                    @if ($yearlyStats->isEmpty())
                        <p class="text-gray-600">Chưa có dữ liệu thống kê thuế. Hãy thực hiện một số khai báo thuế!</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 mb-8">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Năm
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tổng thu nhập trong năm
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tổng thuế TNCN đã nộp trong năm
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($yearlyStats as $yearData)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap font-semibold">
                                                {{ $yearData['year'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ number_format($yearData['total_income'], 0, ',', '.') }} VNĐ
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap font-bold text-lg text-red-700">
                                                {{ number_format($yearData['total_tax'], 0, ',', '.') }} VNĐ
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <h3 class="text-lg font-medium text-gray-900 mb-4 mt-8">Chi tiết theo tháng</h3>
                        @foreach ($yearlyStats as $yearData)
                            <h4 class="font-semibold text-gray-800 text-md mb-2 mt-4">Năm {{ $yearData['year'] }}</h4>
                            <div class="overflow-x-auto mb-6">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tháng
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Thu nhập (tháng)
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Thuế TNCN (tháng)
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @for ($month = 1; $month <= 12; $month++)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">Tháng {{ $month }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ number_format($yearData['monthly_data'][$month]['income'], 0, ',', '.') }} VNĐ
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ number_format($yearData['monthly_data'][$month]['tax'], 0, ',', '.') }} VNĐ
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        @endforeach

                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>