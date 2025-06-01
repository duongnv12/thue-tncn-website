<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    @auth
                        @if (Auth::user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}">
                        @else
                            <a href="{{ route('dashboard') }}">
                        @endif
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        </a>
                    @else
                        <a href="/">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        </a>
                    @endauth
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        @if (Auth::user()->is_admin)
                            {{-- Các liên kết dành cho Admin --}}
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                {{ __('Admin Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                {{ __('Quản lý Người dùng') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.tax_settings.index')" :active="request()->routeIs('admin.tax_settings.*') || request()->routeIs('admin.tax_brackets.*')">
                                {{ __('Cài đặt Thuế') }}
                            </x-nav-link>
                        @else
                            {{-- Các liên kết dành cho người dùng thông thường --}}
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('income_sources.index')" :active="request()->routeIs('income_sources.*')">
                                {{ __('Nguồn Thu nhập') }}
                            </x-nav-link>
                            <x-nav-link :href="route('dependents.index')" :active="request()->routeIs('dependents.*')">
                                {{ __('Người Phụ thuộc') }}
                            </x-nav-link>
                            <x-nav-link :href="route('tax_calculation.index')" :active="request()->routeIs('tax_calculation.index')">
                                {{ __('Tính Thuế TNCN') }}
                            </x-nav-link>
                            <x-nav-link :href="route('tax_declarations.index')" :active="request()->routeIs('tax_declarations.index')">
                                {{ __('Lịch sử Khai báo') }}
                            </x-nav-link>
                            <x-nav-link :href="route('tax_declarations.statistics')" :active="request()->routeIs('tax_declarations.statistics')">
                                {{ __('Thống kê Thuế') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @if (Auth::user()->is_admin)
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Admin Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        {{ __('Quản lý Người dùng') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.tax_settings.index')" :active="request()->routeIs('admin.tax_settings.*') || request()->routeIs('admin.tax_brackets.*')">
                        {{ __('Cài đặt Thuế') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('income_sources.index')" :active="request()->routeIs('income_sources.*')">
                        {{ __('Nguồn Thu nhập') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('dependents.index')" :active="request()->routeIs('dependents.*')">
                        {{ __('Người Phụ thuộc') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('tax_calculation.index')" :active="request()->routeIs('tax_calculation.index')">
                        {{ __('Tính Thuế TNCN') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('tax_declarations.index')" :active="request()->routeIs('tax_declarations.index')">
                        {{ __('Lịch sử Khai báo') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('tax_declarations.statistics')" :active="request()->routeIs('tax_declarations.statistics')">
                        {{ __('Thống kê Thuế') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>