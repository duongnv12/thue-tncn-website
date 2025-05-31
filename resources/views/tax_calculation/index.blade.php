<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tính toán Thuế TNCN') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Chọn năm và nhập các khoản giảm trừ khác để tính toán thuế</h3>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('tax_calculation.calculate') }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="year" :value="__('Năm tính thuế')" />
                            <select id="year" name="year" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                @for ($i = date('Y'); $i >= date('Y') - 10; $i--)
                                    <option value="{{ $i }}" {{ old('year', $currentYear) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            <x-input-error :messages="$errors->get('year')" class="mt-2" />
                        </div>

                        {{-- Các trường nhập liệu mới cho giảm trừ khác --}}
                        <div class="mb-4">
                            <x-input-label for="insurance_deduction_amount" :value="__('Tổng đóng góp Bảo hiểm bắt buộc trong năm (VND)')" />
                            <x-text-input id="insurance_deduction_amount" class="block mt-1 w-full" type="number" name="insurance_deduction_amount" :value="old('insurance_deduction_amount', 0)" min="0" step="any" placeholder="Ví dụ: 10000000 (10 triệu VND)" />
                            <x-input-error :messages="$errors->get('insurance_deduction_amount')" class="mt-2" />
                            <p class="text-sm text-gray-500 mt-1">Tổng số tiền BHXH, BHYT, BHTN đã đóng trong năm.</p>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="charitable_deduction_amount" :value="__('Tổng đóng góp Từ thiện, nhân đạo, khuyến học trong năm (VND)')" />
                            <x-text-input id="charitable_deduction_amount" class="block mt-1 w-full" type="number" name="charitable_deduction_amount" :value="old('charitable_deduction_amount', 0)" min="0" step="any" placeholder="Ví dụ: 5000000 (5 triệu VND)" />
                            <x-input-error :messages="$errors->get('charitable_deduction_amount')" class="mt-2" />
                            <p class="text-sm text-gray-500 mt-1">Số tiền đóng góp từ thiện, nhân đạo, khuyến học (cần có chứng từ hợp lệ).</p>
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Tính toán Thuế') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <div class="mt-6 border-t pt-4">
                        <h4 class="text-md font-medium text-gray-900">Các năm bạn đã có nguồn thu nhập:</h4>
                        @if ($availableYears->isEmpty())
                            <p class="text-sm text-gray-600">Chưa có nguồn thu nhập nào được ghi nhận.</p>
                        @else
                            <ul class="list-disc list-inside mt-2 text-sm text-gray-600">
                                @foreach ($availableYears as $year)
                                    <li>Năm {{ $year }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <p class="mt-4 text-sm text-gray-600">
                            Để tính toán thuế, bạn cần đảm bảo đã nhập đầy đủ các nguồn thu nhập và người phụ thuộc cho năm đã chọn.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>