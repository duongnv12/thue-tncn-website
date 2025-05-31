<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-lg font-medium">Chào mừng, {{ Auth::user()->name }}!</p>
                    <p class="mt-4">Đây là bảng điều khiển quản trị viên của hệ thống:</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
                        <div class="bg-indigo-100 p-4 rounded-lg shadow-sm">
                            <h3 class="font-semibold text-indigo-800">Tổng số Người dùng</h3>
                            <p class="text-3xl font-bold text-indigo-900">{{ $totalUsers }}</p>
                            <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:underline">Quản lý người dùng</a>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-lg shadow-sm">
                            <h3 class="font-semibold text-yellow-800">Tổng số Admin</h3>
                            <p class="text-3xl font-bold text-yellow-900">{{ $totalAdmins }}</p>
                            <span class="text-sm text-gray-600">Tài khoản có quyền quản trị</span>
                        </div>
                        <div class="bg-teal-100 p-4 rounded-lg shadow-sm">
                            <h3 class="font-semibold text-teal-800">Cài đặt Thuế</h3>
                            <p class="text-gray-600">Cấu hình các tham số thuế hệ thống.</p>
                            <a href="{{ route('admin.tax_settings.index') }}" class="text-sm text-teal-600 hover:underline">Điều chỉnh cài đặt</a>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Người dùng mới nhất</h3>
                        @if ($recentUsers->isEmpty())
                            <p class="text-gray-600">Chưa có người dùng nào đăng ký.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tên
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Email
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Ngày đăng ký
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($recentUsers as $user)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->created_at->format('d/m/Y H:i') }}</td>
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
    </div>
</x-app-layout>