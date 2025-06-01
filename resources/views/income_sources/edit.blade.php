<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sửa Nguồn Thu nhập') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('income_sources.update', $incomeSource) }}" class="mt-6 space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Tên nguồn thu nhập')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $incomeSource->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="amount" :value="__('Số tiền (VNĐ)')" />
                            <x-text-input id="amount" name="amount" type="number" step="any" min="0" class="mt-1 block w-full" :value="old('amount', $incomeSource->amount)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('amount')" />
                        </div>

                        <div>
                            <x-input-label for="frequency" :value="__('Tần suất')" />
                            <select id="frequency" name="frequency" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="monthly" {{ old('frequency', $incomeSource->frequency) == 'monthly' ? 'selected' : '' }}>Hàng tháng</option>
                                <option value="one-time" {{ old('frequency', $incomeSource->frequency) == 'one-time' ? 'selected' : '' }}>Một lần</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('frequency')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Mô tả (tùy chọn)')" />
                            <x-text-area id="description" name="description" class="mt-1 block w-full">{{ old('description', $incomeSource->description) }}</x-text-area>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Lưu Thay đổi') }}</x-primary-button>
                            <a href="{{ route('income_sources.index') }}" class="text-gray-600 hover:text-gray-900">{{ __('Hủy') }}</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>