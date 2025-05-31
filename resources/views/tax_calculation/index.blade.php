<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tính Thuế Thu nhập cá nhân') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <p class="mb-4 text-gray-700">
                        Hệ thống sẽ tự động tổng hợp thu nhập hàng tháng từ các "Nguồn Thu nhập" và số lượng "Người phụ thuộc" bạn đã khai báo để tính thuế TNCN.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-blue-50 p-4 rounded-lg shadow-sm">
                            <h3 class="font-semibold text-blue-800 text-lg mb-2">Thông tin Thu nhập & Giảm trừ (Tháng {{ $currentMonth }}/{{ $currentYear }})</h3>
                            <div class="mb-2">
                                <span class="font-medium">Tổng thu nhập chịu thuế:</span> {{ number_format($totalIncome, 0, ',', '.') }} VNĐ
                            </div>
                            <div class="mb-2">
                                <span class="font-medium">Số người phụ thuộc:</span> {{ $numDependents }} người
                            </div>
                            @if($totalDeductions !== null)
                                <div class="mb-2">
                                    <span class="font-medium">Tổng giảm trừ gia cảnh:</span> {{ number_format($totalDeductions, 0, ',', '.') }} VNĐ
                                </div>
                            @endif
                        </div>

                        <div class="bg-green-50 p-4 rounded-lg shadow-sm">
                            <h3 class="font-semibold text-green-800 text-lg mb-2">Kết quả tính toán (Nếu có)</h3>
                            @if ($calculatedTax !== null)
                                <div class="mb-2">
                                    <span class="font-medium">Thu nhập tính thuế:</span> {{ number_format($taxableIncome, 0, ',', '.') }} VNĐ
                                </div>
                                <div class="mb-2 text-xl font-bold text-green-900">
                                    <span class="font-medium">Thuế TNCN phải nộp:</span> {{ number_format($calculatedTax, 0, ',', '.') }} VNĐ
                                </div>
                            @else
                                <p class="text-gray-600">Chưa có kết quả tính thuế. Nhấn "Tính và Lưu khai báo" bên dưới.</p>
                            @endif
                        </div>
                    </div>

                    <form method="POST" action="{{ route('tax_calculation.calculate_and_save') }}">
                        @csrf
                        <x-primary-button>
                            {{ __('Tính và Lưu khai báo cho Tháng hiện tại') }}
                        </x-primary-button>
                        <p class="text-sm text-gray-500 mt-2">
                            (Tháng hiện tại: {{ $currentMonth }}/{{ $currentYear }})
                        </p>
                    </form>

                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Các bước để tính thuế chính xác:</h3>
                        <ol class="list-decimal list-inside space-y-2 text-gray-700">
                            <li>Đảm bảo bạn đã khai báo đầy đủ và chính xác tất cả các <a href="{{ route('income_sources.index') }}" class="text-indigo-600 hover:underline">nguồn thu nhập hàng tháng</a>.</li>
                            <li>Khai báo đầy đủ và chính xác tất cả <a href="{{ route('dependents.index') }}" class="text-indigo-600 hover:underline">người phụ thuộc</a> của bạn.</li>
                            <li>Nhấn nút "Tính và Lưu khai báo" để hệ thống tự động tính toán dựa trên dữ liệu hiện có và lưu lại.</li>
                            <li>Bạn có thể xem lại các khai báo đã lưu trong <a href="{{ route('tax_declarations.index') }}" class="text-indigo-600 hover:underline">Lịch sử Khai báo</a>.</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>