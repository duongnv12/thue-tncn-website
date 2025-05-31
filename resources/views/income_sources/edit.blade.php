<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chỉnh sửa Nguồn Thu nhập') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin Nguồn Thu nhập</h3>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('income_sources.update', $incomeSource) }}">
                        @csrf
                        @method('PUT')

                        {{-- Tên nguồn thu nhập --}}
                        <div class="mb-4">
                            <x-input-label for="source_name" :value="__('Tên nguồn thu nhập')" />
                            <x-text-input id="source_name" class="block mt-1 w-full" type="text" name="source_name" :value="old('source_name', $incomeSource->source_name)" required autofocus placeholder="Ví dụ: Công ty ABC, Thu nhập Freelance..." />
                            <x-input-error :messages="$errors->get('source_name')" class="mt-2" />
                        </div>

                        {{-- Loại thu nhập (Không cần placeholder cho select) --}}
                        <div class="mb-4">
                            <x-input-label for="type" :value="__('Loại thu nhập')" />
                            <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="salary" {{ old('type', $incomeSource->type) == 'salary' ? 'selected' : '' }}>Tiền lương, tiền công</option>
                                <option value="business" {{ old('type', $incomeSource->type) == 'business' ? 'selected' : '' }}>Kinh doanh</option>
                                <option value="capital_investment" {{ old('type', $incomeSource->type) == 'capital_investment' ? 'selected' : '' }}>Đầu tư vốn</option>
                                <option value="other" {{ old('type', $incomeSource->type) == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        {{-- Năm thu nhập --}}
                        <div class="mb-4">
                            <x-input-label for="year" :value="__('Năm thu nhập')" />
                            <x-text-input id="year" class="block mt-1 w-full" type="number" name="year" :value="old('year', $incomeSource->year)" required placeholder="Ví dụ: 2024" />
                            <x-input-error :messages="$errors->get('year')" class="mt-2" />
                        </div>

                        {{-- Tổng thu nhập chịu thuế --}}
                        <div class="mb-4">
                            <x-input-label for="total_taxable_income" :value="__('Tổng thu nhập chịu thuế (VND)')" />
                            <x-text-input id="total_taxable_income" class="block mt-1 w-full" type="number" name="total_taxable_income" :value="old('total_taxable_income', $incomeSource->total_taxable_income)" required min="0" step="any" placeholder="Ví dụ: 150000000 (150 triệu)" />
                            <x-input-error :messages="$errors->get('total_taxable_income')" class="mt-2" />
                        </div>

                        {{-- Thuế đã khấu trừ --}}
                        <div class="mb-4">
                            <x-input-label for="tax_withheld" :value="__('Thuế đã khấu trừ tại nguồn (VND) (Nếu có)')" />
                            <x-text-input id="tax_withheld" class="block mt-1 w-full" type="number" name="tax_withheld" :value="old('tax_withheld', $incomeSource->tax_withheld)" min="0" step="any" placeholder="Ví dụ: 10000000 (10 triệu)" />
                            <x-input-error :messages="$errors->get('tax_withheld')" class="mt-2" />
                        </div>

                        {{-- Ghi chú --}}
                        <div class="mb-4">
                            <x-input-label for="notes" :value="__('Ghi chú (Tùy chọn)')" />
                            <x-text-area id="notes" name="notes" class="block mt-1 w-full" rows="3" placeholder="Ví dụ: Thu nhập từ dự án Y...">{{ old('notes', $incomeSource->notes) }}</x-text-area>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Cập nhật Nguồn Thu nhập') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-app-layout>