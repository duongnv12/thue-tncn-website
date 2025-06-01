<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Ứng dụng Tính Thuế TNCN') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            transition: background 0.5s;
        }
        .hero-background {
            background: linear-gradient(120deg, #6366f1 0%, #8b5cf6 100%);
        }
        @media (prefers-color-scheme: dark) {
            .hero-background {
                background: linear-gradient(120deg, #312e81 0%, #6d28d9 100%);
            }
        }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen flex flex-col justify-between bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-200">

        <!-- Header -->
        <header class="absolute inset-x-0 top-0 z-50">
            <nav class="flex items-center justify-between p-6 lg:px-8 bg-white/80 dark:bg-gray-900/80 shadow-lg rounded-b-2xl backdrop-blur-md border-b border-gray-200 dark:border-gray-800 transition-all duration-300" aria-label="Global">
                <div class="flex lg:flex-1">
                    <a href="{{ url('/') }}" class="-m-1.5 p-1.5 flex items-center hover:scale-105 transition-transform">
                        {{-- Logo --}}
                        <svg class="h-8 w-auto text-indigo-600 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="ml-3 text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ config('app.name', 'Thuế TNCN') }}</span>
                    </a>
                </div>
                @if (Route::has('login'))
                    <div class="flex lg:flex-1 lg:justify-end gap-x-2">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 px-4 py-2 rounded-lg transition">
                                Bảng điều khiển <span aria-hidden="true">&rarr;</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 px-4 py-2 rounded-lg transition">
                                Đăng nhập <span aria-hidden="true">&rarr;</span>
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-2 text-sm font-semibold leading-6 text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 px-4 py-2 rounded-lg shadow transition">
                                    Đăng ký <span aria-hidden="true">&rarr;</span>
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </nav>
        </header>

        <!-- Hero Section -->
        <main class="flex-grow flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 hero-background relative overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-400/30 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute bottom-0 left-0 w-80 h-80 bg-violet-400/20 rounded-full blur-2xl animate-pulse"></div>
            </div>
            <div class="relative text-center text-white max-w-4xl mx-auto bg-white/10 dark:bg-gray-900/30 rounded-3xl shadow-2xl p-12 backdrop-blur-md border border-white/20">
                <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl leading-tight mb-6 drop-shadow-lg">
                    <span class="block">Đơn giản hóa việc</span>
                    <span class="block text-yellow-300 animate-pulse">Tính và Quản lý Thuế TNCN của bạn</span>
                </h1>
                <p class="mt-6 text-lg leading-8 text-indigo-100 sm:max-w-xl mx-auto">
                    Ứng dụng của chúng tôi giúp bạn dễ dàng tính toán thuế thu nhập cá nhân, theo dõi các khoản thu nhập, quản lý người phụ thuộc và xem báo cáo thống kê trực quan mọi lúc, mọi nơi.
                </p>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <a href="{{ route('register') }}" class="rounded-full bg-gradient-to-r from-yellow-300 to-indigo-500 px-8 py-3 text-base font-bold text-indigo-900 shadow-lg hover:scale-105 hover:from-yellow-400 hover:to-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white transition duration-150 ease-in-out">
                        Bắt đầu miễn phí
                    </a>
                    <a href="{{ route('login') }}" class="text-base font-semibold leading-6 text-white hover:text-yellow-300 transition duration-150 ease-in-out">
                        Đăng nhập <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </main>

        <!-- Features Section -->
        <section class="py-16 bg-white dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Các Tính Năng</h2>
                    <p class="mt-2 text-3xl font-extrabold leading-8 tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                        Tất cả những gì bạn cần để quản lý thuế TNCN
                    </p>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 dark:text-gray-400 lg:mx-auto">
                        Chúng tôi cung cấp các công cụ mạnh mẽ giúp bạn tiết kiệm thời gian và giảm thiểu rắc rối với thuế.
                    </p>
                </div>

                <div class="mt-10">
                    <dl class="space-y-10 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10 md:space-y-0">
                        <div class="relative group hover:scale-105 transition-transform duration-200">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-tr from-indigo-500 to-violet-500 text-white shadow-lg group-hover:from-yellow-400 group-hover:to-indigo-500 transition">
                                    <svg class="h-6 w-6 animate-bounce" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-semibold text-gray-900 dark:text-white">Tính Thuế Chính Xác</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500 dark:text-gray-400">
                                Dựa trên các quy định thuế mới nhất, đảm bảo kết quả tính toán luôn chính xác.
                            </dd>
                        </div>
                        <div class="relative group hover:scale-105 transition-transform duration-200">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-tr from-indigo-500 to-violet-500 text-white shadow-lg group-hover:from-yellow-400 group-hover:to-indigo-500 transition">
                                    <svg class="h-6 w-6 animate-spin" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9m2.25 2.25 2.25 2.25m0-4.5H18m0 0v4.5m0-4.5L15.75 9m2.25 2.25 2.25 2.25m0 0v4.5m0-4.5h4.5m-4.5 0L21 21" />
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-semibold text-gray-900 dark:text-white">Quản lý Thu nhập & Giảm trừ</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500 dark:text-gray-400">
                                Dễ dàng thêm, sửa, xóa các nguồn thu nhập và thông tin người phụ thuộc của bạn.
                            </dd>
                        </div>
                        <div class="relative group hover:scale-105 transition-transform duration-200">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-tr from-indigo-500 to-violet-500 text-white shadow-lg group-hover:from-yellow-400 group-hover:to-indigo-500 transition">
                                    <svg class="h-6 w-6 animate-pulse" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125l5.25 5.25L21 7.5" />
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-semibold text-gray-900 dark:text-white">Thống kê Chi tiết</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500 dark:text-gray-400">
                                Xem báo cáo tổng quan và chi tiết thuế TNCN theo tháng, quý, năm.
                            </dd>
                        </div>
                        <div class="relative group hover:scale-105 transition-transform duration-200">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-tr from-indigo-500 to-violet-500 text-white shadow-lg group-hover:from-yellow-400 group-hover:to-indigo-500 transition">
                                    <svg class="h-6 w-6 animate-bounce" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3V12" />
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-semibold text-gray-900 dark:text-white">Xuất báo cáo PDF</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500 dark:text-gray-400">
                                Dễ dàng xuất các bản khai báo thuế đã lưu thành file PDF để lưu trữ hoặc nộp.
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </section>

        <!-- Call to Action Section -->
        <section class="bg-indigo-700 dark:bg-indigo-900 text-white py-16 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto text-center">
                <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">
                    Sẵn sàng quản lý thuế của bạn?
                </h2>
                <p class="mt-4 text-lg leading-6 text-indigo-200">
                    Tham gia cùng hàng ngàn người dùng khác đang đơn giản hóa việc quản lý thuế cá nhân.
                </p>
                <div class="mt-8 flex justify-center gap-x-6">
                    <a href="{{ route('register') }}" class="rounded-full bg-gradient-to-r from-yellow-300 to-indigo-500 px-8 py-3 text-base font-bold text-indigo-900 shadow-lg hover:scale-105 hover:from-yellow-400 hover:to-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white transition duration-150 ease-in-out">
                        Đăng ký ngay
                    </a>
                    <a href="{{ route('login') }}" class="text-base font-semibold leading-6 text-white hover:text-yellow-300 transition duration-150 ease-in-out">
                        Bạn đã có tài khoản? Đăng nhập
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-8 bg-gradient-to-r from-indigo-900 via-indigo-800 to-violet-900 text-gray-300 text-center text-sm shadow-inner">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                &copy; {{ date('Y') }} <span class="font-bold text-white">{{ config('app.name', 'Ứng dụng Tính Thuế TNCN') }}</span>. All rights reserved.
                <p class="mt-2">Được xây dựng với <span class="text-indigo-400 font-semibold">Laravel</span> & <span class="text-yellow-300 font-semibold">Tailwind CSS</span>.</p>
            </div>
        </footer>
    </div>
</body>
</html>