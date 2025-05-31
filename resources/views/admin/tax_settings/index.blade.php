<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cài đặt Thuế') }}
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

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Giảm trừ Gia cảnh</h3>
                    <form method="POST" action="{{ route('admin.tax_settings.update') }}">
                        @csrf
                        @method('PUT')

                        @foreach ($taxSettings as $setting)
                            <div class="mb-4">
                                <x-input-label for="{{ $setting->setting_key }}" :value="__($setting->description)" />
                                <x-text-input id="{{ $setting->setting_key }}" class="block mt-1 w-full" type="number" name="{{ $setting->setting_key }}" :value="old($setting->setting_key, $setting->setting_value)" required min="0" />
                                <x-input-error :messages="$errors->get($setting->setting_key)" class="mt-2" />
                            </div>
                        @endforeach

                        <x-primary-button class="mt-4">
                            {{ __('Cập nhật Giảm trừ') }}
                        </x-primary-button>
                    </form>

                    <h3 class="text-lg font-medium text-gray-900 mb-4 mt-8 pt-8 border-t border-gray-200">Biểu thuế Lũy tiến từng phần</h3>
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('admin.tax_brackets.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Thêm bậc thuế
                        </a>
                    </div>

                    @if ($taxBrackets->isEmpty())
                        <p class="text-gray-600">Chưa có bậc thuế nào được cấu hình.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Bậc
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Thu nhập từ
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Đến
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tỷ lệ (%)
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Hành động
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($taxBrackets as $bracket)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">Bậc {{ $bracket->bracket_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($bracket->min_income, 0, ',', '.') }} VNĐ</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $bracket->max_income ? number_format($bracket->max_income, 0, ',', '.') . ' VNĐ' : 'Trở lên' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $bracket->tax_rate }}%</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.tax_brackets.edit', $bracket) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Sửa</a>
                                                <form action="{{ route('admin.tax_brackets.destroy', $bracket) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Bạn có chắc chắn muốn xóa bậc thuế này không?')">Xóa</button>
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