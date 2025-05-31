<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Thêm Bậc thuế mới') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.tax_brackets.store') }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="bracket_number" :value="__('Bậc (ví dụ: 1, 2, ...)')" />
                            <x-text-input id="bracket_number" class="block mt-1 w-full" type="number" name="bracket_number" :value="old('bracket_number')" required min="1" />
                            <x-input-error :messages="$errors->get('bracket_number')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="min_income" :value="__('Từ mức thu nhập (VNĐ)')" />
                            <x-text-input id="min_income" class="block mt-1 w-full" type="number" name="min_income" :value="old('min_income')" required min="0" step="any" />
                            <x-input-error :messages="$errors->get('min_income')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="max_income" :value="__('Đến mức thu nhập (VNĐ) - Để trống nếu là bậc cuối cùng')" />
                            <x-text-input id="max_income" class="block mt-1 w-full" type="number" name="max_income" :value="old('max_income')" min="0" step="any" />
                            <x-input-error :messages="$errors->get('max_income')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="tax_rate" :value="__('Tỷ lệ thuế (%)')" />
                            <x-text-input id="tax_rate" class="block mt-1 w-full" type="number" name="tax_rate" :value="old('tax_rate')" required min="0" max="100" step="0.01" />
                            <x-input-error :messages="$errors->get('tax_rate')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Thêm Bậc thuế') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>