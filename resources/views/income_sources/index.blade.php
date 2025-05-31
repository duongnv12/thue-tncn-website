<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quản lý Nguồn Thu nhập') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Danh sách Nguồn Thu nhập của bạn</h3>
                        <a href="{{ route('income_sources.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Thêm nguồn thu nhập
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if ($incomeSources->isEmpty())
                        <p class="text-gray-600">Bạn chưa có nguồn thu nhập nào. Hãy thêm một nguồn!</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tên nguồn
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Số tiền (hàng tháng)
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tần suất
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Mô tả
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Hành động
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($incomeSources as $source)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $source->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ number_format($source->amount, 0, ',', '.') }} VNĐ
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ ucfirst($source->frequency) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ Str::limit($source->description, 50) ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('income_sources.edit', $source) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Sửa</a>
                                                <form action="{{ route('income_sources.destroy', $source) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Bạn có chắc chắn muốn xóa nguồn thu nhập này không?')">Xóa</button>
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