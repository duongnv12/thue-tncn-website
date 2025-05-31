<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trang chủ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ __("Chào mừng bạn đã đăng nhập!") }}
                    </h3>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ __("Bạn có thể bắt đầu quản lý thông tin thuế của mình tại đây.") }}
                    </p>

                    <div class="mt-4">
                        <a href="#" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Thêm nguồn thu nhập mới') }}
                        </a>
                        <a href="#" class="ml-4 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Xem lịch sử tính toán') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>