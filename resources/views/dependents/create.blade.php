<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Thêm Người Phụ thuộc mới') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('dependents.store') }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Họ và tên người phụ thuộc')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="date_of_birth" :value="__('Ngày sinh')" />
                            <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth" :value="old('date_of_birth')" required />
                            <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="relationship" :value="__('Mối quan hệ')" />
                            <x-text-input id="relationship" class="block mt-1 w-full" type="text" name="relationship" :value="old('relationship')" required />
                            <x-input-error :messages="$errors->get('relationship')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="tax_code" :value="__('Mã số thuế người phụ thuộc (tùy chọn)')" />
                            <x-text-input id="tax_code" class="block mt-1 w-full" type="text" name="tax_code" :value="old('tax_code')" />
                            <x-input-error :messages="$errors->get('tax_code')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Thêm Người Phụ thuộc') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>