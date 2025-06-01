<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lịch sử Khai báo Thuế') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">

                <h3 class="text-xl font-semibold mb-6">Danh sách Khai báo Thuế của bạn</h3>

                @if(session('status'))
                    <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                        <p>{{ session('status') }}</p>
                    </div>
                @endif

                @if($declarations->isEmpty())
                    <p>Bạn chưa có khai báo thuế nào.</p>
                    <a href="{{ route('tax_calculation.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-4">
                        {{ __('Tính toán và khai báo ngay') }}
                    </a>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tháng/Năm</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng thu nhập</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng giảm trừ</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thuế TNCN phải nộp</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($declarations as $declaration)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $declaration->declaration_month }}/{{ $declaration->declaration_year }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($declaration->total_income, 0, ',', '.') }} VNĐ</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($declaration->total_deductions, 0, ',', '.') }} VNĐ</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($declaration->calculated_tax, 0, ',', '.') }} VNĐ</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('tax_declarations.show', $declaration) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Xem chi tiết</a>
                                            <a href="{{ route('tax_declarations.export_pdf', $declaration) }}" class="text-blue-600 hover:text-blue-900 mr-3">Xuất PDF</a> {{-- NÚT XUẤT PDF --}}
                                            <form action="{{ route('tax_declarations.destroy', $declaration) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khai báo này không?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Xóa</button>
                                            </form>
                                        </td>
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