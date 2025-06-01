<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cài đặt Thuế và Bảo hiểm') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Thành công!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.103l-2.651 2.651a1.2 1.2 0 1 1-1.697-1.697L8.303 9.406 5.652 6.755a1.2 1.2 0 0 1 1.697-1.697L10 7.709l2.651-2.651a1.2 1.2 0 0 1 1.697 1.697l-2.651 2.651 2.651 2.651a1.2 1.2 0 0 1 0 1.697z"/></svg>
                    </span>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h2 class="text-lg font-medium text-gray-900">Cài đặt Giảm trừ Gia cảnh và Bảo hiểm</h2>
                <p class="mt-1 text-sm text-gray-600">Cập nhật các mức giảm trừ và tỷ lệ đóng bảo hiểm bắt buộc.</p>

                <form method="post" action="{{ route('admin.tax_settings.update') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('put')

                    <div>
                        <x-input-label for="personal_deduction" :value="__('Mức giảm trừ bản thân (VNĐ/tháng)')" />
                        <x-text-input id="personal_deduction" name="personal_deduction" type="number" step="any" min="0" class="mt-1 block w-full" :value="old('personal_deduction', $taxSettings['personal_deduction']->setting_value ?? 0)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('personal_deduction')" />
                    </div>

                    <div>
                        <x-input-label for="dependent_deduction" :value="__('Mức giảm trừ cho mỗi người phụ thuộc (VNĐ/tháng)')" />
                        <x-text-input id="dependent_deduction" name="dependent_deduction" type="number" step="any" min="0" class="mt-1 block w-full" :value="old('dependent_deduction', $taxSettings['dependent_deduction']->setting_value ?? 0)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('dependent_deduction')" />
                    </div>

                    <hr class="my-6">

                    <h3 class="text-md font-medium text-gray-900 mt-8">Cài đặt Bảo hiểm Bắt buộc (Tỷ lệ đóng của người lao động)</h3>
                    <p class="mt-1 text-sm text-gray-600">Các tỷ lệ đóng bảo hiểm được tính trên thu nhập Gross.</p>

                    <div>
                        <x-input-label for="bhxh_employee_rate" :value="__('Tỷ lệ đóng BHXH (%)')" />
                        <x-text-input id="bhxh_employee_rate" name="bhxh_employee_rate" type="number" step="0.1" min="0" max="100" class="mt-1 block w-full" :value="old('bhxh_employee_rate', $taxSettings['bhxh_employee_rate']->setting_value ?? 0)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('bhxh_employee_rate')" />
                    </div>

                    <div>
                        <x-input-label for="bhyc_employee_rate" :value="__('Tỷ lệ đóng BHYT (%)')" />
                        <x-text-input id="bhyc_employee_rate" name="bhyc_employee_rate" type="number" step="0.1" min="0" max="100" class="mt-1 block w-full" :value="old('bhyc_employee_rate', $taxSettings['bhyc_employee_rate']->setting_value ?? 0)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('bhyc_employee_rate')" />
                    </div>

                    <div>
                        <x-input-label for="bhtn_employee_rate" :value="__('Tỷ lệ đóng BHTN (%)')" />
                        <x-text-input id="bhtn_employee_rate" name="bhtn_employee_rate" type="number" step="0.1" min="0" max="100" class="mt-1 block w-full" :value="old('bhtn_employee_rate', $taxSettings['bhtn_employee_rate']->setting_value ?? 0)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('bhtn_employee_rate')" />
                    </div>

                    <div>
                        <x-input-label for="insurance_base_cap" :value="__('Mức trần tiền lương đóng BHXH/BHYT (VNĐ/tháng)')" />
                        <x-text-input id="insurance_base_cap" name="insurance_base_cap" type="number" step="any" min="0" class="mt-1 block w-full" :value="old('insurance_base_cap', $taxSettings['insurance_base_cap']->setting_value ?? 0)" required />
                        <p class="mt-1 text-xs text-gray-500">Mức lương đóng BHXH, BHYT không vượt quá mức này (thường là 20 lần mức lương cơ sở).</p>
                        <x-input-error class="mt-2" :messages="$errors->get('insurance_base_cap')" />
                    </div>

                    <div>
                        <x-input-label for="regional_minimum_wage" :value="__('Mức lương tối thiểu vùng (VNĐ/tháng)')" />
                        <x-text-input id="regional_minimum_wage" name="regional_minimum_wage" type="number" step="any" min="0" class="mt-1 block w-full" :value="old('regional_minimum_wage', $taxSettings['regional_minimum_wage']->setting_value ?? 0)" required />
                        <p class="mt-1 text-xs text-gray-500">Dùng làm cơ sở cho mức trần BHTN (20 lần mức lương tối thiểu vùng).</p>
                        <x-input-error class="mt-2" :messages="$errors->get('regional_minimum_wage')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Lưu Cài đặt Chung') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Quản lý Bậc Thuế TNCN</h2>
                    <a href="{{ route('admin.tax_brackets.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Thêm Bậc Thuế Mới') }}
                    </a>
                </div>
                <p class="mt-1 text-sm text-gray-600">Thêm, sửa, xóa các bậc thuế TNCN lũy tiến.</p>

                <div class="mt-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bậc
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Từ mức (VNĐ)
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Đến mức (VNĐ)
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Thuế suất (%)
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hành động
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($taxBrackets as $bracket)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $bracket->bracket_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($bracket->min_income, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $bracket->max_income ? number_format($bracket->max_income, 0, ',', '.') : 'Trên' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $bracket->tax_rate }}%
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.tax_brackets.edit', $bracket) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Sửa</a>
                                        <form action="{{ route('admin.tax_brackets.destroy', $bracket) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bậc thuế này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Chưa có bậc thuế nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>