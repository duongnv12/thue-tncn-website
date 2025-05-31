<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard của bạn') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-lg font-medium">Chào mừng, {{ Auth::user()->name }}!</p>
                    <p class="mt-4">Đây là tổng quan về tài khoản của bạn:</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
                        <div class="bg-blue-100 p-4 rounded-lg shadow-sm">
                            <h3 class="font-semibold text-blue-800">Tổng số Nguồn Thu nhập</h3>
                            <p class="text-3xl font-bold text-blue-900">{{ $totalIncomeSources }}</p>
                            <a href="{{ route('income_sources.index') }}" class="text-sm text-blue-600 hover:underline">Xem chi tiết</a>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg shadow-sm">
                            <h3 class="font-semibold text-green-800">Tổng số Người Phụ thuộc</h3>
                            <p class="text-3xl font-bold text-green-900">{{ $totalDependents }}</p>
                            <a href="{{ route('dependents.index') }}" class="text-sm text-green-600 hover:underline">Xem chi tiết</a>
                        </div>
                        {{-- Thêm các card thống kê khác ở đây nếu cần --}}
                        <div class="bg-purple-100 p-4 rounded-lg shadow-sm">
                            <h3 class="font-semibold text-purple-800">Tính thuế TNCN</h3>
                            <p class="text-gray-600">Bắt đầu tính thuế cho tháng này.</p>
                            <a href="{{ route('tax_calculation.index') }}" class="text-sm text-purple-600 hover:underline">Đi đến trang tính thuế</a>
                        </div>
                    </div>

                    <div class="mt-8 text-sm text-gray-500">
                        Bạn có thể sử dụng thanh điều hướng bên trên để quản lý thu nhập, người phụ thuộc và tính toán thuế.
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>