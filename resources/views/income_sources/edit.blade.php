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
                    <form method="POST" action="{{ route('income_sources.update', $incomeSource) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Tên nguồn thu nhập')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $incomeSource->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="amount" :value="__('Số tiền (VNĐ/tháng)')" />
                            <x-text-input id="amount" class="block mt-1 w-full" type="number" name="amount" :value="old('amount', $incomeSource->amount)" required min="0" step="any" />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="frequency" :value="__('Tần suất')" />
                            <select id="frequency" name="frequency" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                <option value="monthly" {{ old('frequency', $incomeSource->frequency) == 'monthly' ? 'selected' : '' }}>Hàng tháng</option>
                                <option value="yearly" {{ old('frequency', $incomeSource->frequency) == 'yearly' ? 'selected' : '' }}>Hàng năm (cần xử lý thủ công cho tháng)</option>
                                <option value="one-time" {{ old('frequency', $incomeSource->frequency) == 'one-time' ? 'selected' : '' }}>Một lần (cần xử lý thủ công cho tháng)</option>
                            </select>
                            <x-input-error :messages="$errors->get('frequency')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Mô tả (tùy chọn)')" />
                            <textarea id="description" name="description" rows="3" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('description', $incomeSource->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
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
    </div>
</x-app-layout>