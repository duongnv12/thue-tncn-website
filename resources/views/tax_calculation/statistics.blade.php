<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Thống kê Thu nhập & Thuế') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Biểu đồ Tổng quan Thu nhập & Thuế theo năm</h3>

                @if (empty($chartData['labels']))
                    <x-alert type="info" message="Chưa có dữ liệu thống kê. Vui lòng thêm nguồn thu nhập và thực hiện tính toán thuế cho các năm khác nhau." />
                @else
                    <div class="mb-6">
                        <canvas id="taxChart"></canvas>
                    </div>

                    <h4 class="text-md font-medium text-gray-900 mb-2">Dữ liệu chi tiết:</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Năm</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng thu nhập chịu thuế</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng thuế phải nộp</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng thuế đã khấu trừ</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($chartData['labels'] as $key => $year)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $year }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($chartData['totalGrossIncome'][$key], 0, ',', '.') }} VND</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($chartData['totalTaxPayable'][$key], 0, ',', '.') }} VND</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($chartData['totalTaxWithheld'][$key], 0, ',', '.') }} VND</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-card>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

        @if (!empty($chartData['labels']))
            <script>
                const ctx = document.getElementById('taxChart').getContext('2d');
                const taxChart = new Chart(ctx, {
                    type: 'bar', // Có thể thử 'line'
                    data: {
                        labels: @json($chartData['labels']),
                        datasets: [
                            {
                                label: 'Tổng thu nhập chịu thuế (VND)',
                                data: @json($chartData['totalGrossIncome']),
                                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1,
                                borderRadius: 5,
                            },
                            {
                                label: 'Tổng thuế phải nộp (VND)',
                                data: @json($chartData['totalTaxPayable']),
                                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1,
                                borderRadius: 5,
                            },
                            {
                                label: 'Tổng thuế đã khấu trừ (VND)',
                                data: @json($chartData['totalTaxWithheld']),
                                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1,
                                borderRadius: 5,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, // Quan trọng để biểu đồ không bị kéo giãn quá mức
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Năm'
                                },
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Số tiền (VND)'
                                },
                                ticks: {
                                    callback: function(value, index, values) {
                                        // Định dạng số tiền
                                        return value.toLocaleString('vi-VN') + ' VND';
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += context.parsed.y.toLocaleString('vi-VN') + ' VND';
                                        }
                                        return label;
                                    }
                                }
                            },
                            legend: {
                                display: true,
                                position: 'top',
                            }
                        }
                    }
                });
            </script>
        @endif
    @endpush
</x-app-layout>