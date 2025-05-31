<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lịch sử Khai báo Thuế TNCN') }}
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
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @if ($declarations->isEmpty())
                        <p class="text-gray-600">Bạn chưa có khai báo thuế nào. Hãy đi tới trang <a href="{{ route('tax_calculation.index') }}" class="text-indigo-600 hover:underline">Tính Thuế TNCN</a> để thực hiện khai báo đầu tiên!</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kỳ khai báo
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tổng thu nhập
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tổng giảm trừ
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Thu nhập tính thuế
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Thuế TNCN phải nộp
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ngày khai báo
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Hành động
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($declarations as $declaration)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                Tháng {{ $declaration->declaration_month }}/{{ $declaration->declaration_year }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ number_format($declaration->total_income, 0, ',', '.') }} VNĐ
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ number_format($declaration->total_deductions, 0, ',', '.') }} VNĐ
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ number_format($declaration->taxable_income, 0, ',', '.') }} VNĐ
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap font-bold text-lg">
                                                {{ number_format($declaration->calculated_tax, 0, ',', '.') }} VNĐ
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $declaration->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-2">
                                                <a href="{{ route('tax_declarations.export_pdf', $declaration) }}" class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    PDF
                                                </a>
                                                <form action="{{ route('tax_declarations.destroy', $declaration) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150" onclick="return confirm('Bạn có chắc chắn muốn xóa khai báo này không?')">
                                                        Xóa
                                                    </button>
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
    </div>
</x-app-layout>