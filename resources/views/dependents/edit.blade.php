<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chỉnh sửa Người phụ thuộc') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin Người phụ thuộc</h3>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('dependents.update', $dependent) }}">
                        @csrf
                        @method('PUT')

                        {{-- Họ và tên --}}
                        <div class="mb-4">
                            <x-input-label for="full_name" :value="__('Họ và tên')" />
                            <x-text-input id="full_name" class="block mt-1 w-full" type="text" name="full_name" :value="old('full_name', $dependent->full_name)" required autofocus placeholder="Ví dụ: Nguyễn Văn A" />
                            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                        </div>

                        {{-- Ngày sinh --}}
                        <div class="mb-4">
                            <x-input-label for="date_of_birth" :value="__('Ngày sinh (Tùy chọn)')" />
                            <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth" :value="old('date_of_birth', $dependent->date_of_birth)" placeholder="Ví dụ: 2005-12-31" />
                            <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                        </div>

                        {{-- Số CCCD/CMND --}}
                        <div class="mb-4">
                            <x-input-label for="citizen_id" :value="__('Số CCCD/CMND (Tùy chọn)')" />
                            <x-text-input id="citizen_id" class="block mt-1 w-full" type="text" name="citizen_id" :value="old('citizen_id', $dependent->citizen_id)" placeholder="Ví dụ: 0123456789" />
                            <x-input-error :messages="$errors->get('citizen_id')" class="mt-2" />
                        </div>

                        {{-- Mối quan hệ --}}
                        <div class="mb-4">
                            <x-input-label for="relationship" :value="__('Mối quan hệ')" />
                            <select id="relationship" name="relationship" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="con" {{ old('relationship', $dependent->relationship) == 'con' ? 'selected' : '' }}>Con</option>
                                <option value="vo" {{ old('relationship', $dependent->relationship) == 'vo' ? 'selected' : '' }}>Vợ</option>
                                <option value="chong" {{ old('relationship', $dependent->relationship) == 'chong' ? 'selected' : '' }}>Chồng</option>
                                <option value="cha" {{ old('relationship', $dependent->relationship) == 'cha' ? 'selected' : '' }}>Cha</option>
                                <option value="me" {{ old('relationship', $dependent->relationship) == 'me' ? 'selected' : '' }}>Mẹ</option>
                                <option value="khac" {{ old('relationship', $dependent->relationship) == 'khac' ? 'selected' : '' }}>Khác</option>
                            </select>
                            <x-input-error :messages="$errors->get('relationship')" class="mt-2" />
                        </div>

                        {{-- Số tháng tính giảm trừ --}}
                        <div class="mb-4">
                            <x-input-label for="months_registered" :value="__('Số tháng tính giảm trừ')" />
                            <x-text-input id="months_registered" class="block mt-1 w-full" type="number" name="months_registered" :value="old('months_registered', $dependent->months_registered)" required min="1" max="12" placeholder="Ví dụ: 12" />
                            <x-input-error :messages="$errors->get('months_registered')" class="mt-2" />
                        </div>

                        {{-- Ghi chú --}}
                        <div class="mb-4">
                            <x-input-label for="notes" :value="__('Ghi chú (Tùy chọn)')" />
                            <x-text-area id="notes" name="notes" class="block mt-1 w-full" rows="3" placeholder="Ví dụ: Bắt đầu tính từ tháng 3/2024">{{ old('notes', $dependent->notes) }}</x-text-area>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Cập nhật Người phụ thuộc') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>